<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // 🔍 Получаем фильтры с дефолтами
        $period = $request->input('period', 'month');
        $carId = $request->input('car_id');
        $category = $request->input('category');
        $dateFrom = $request->input('date_from', now()->subYear()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // 🔧 Базовый запрос
        $query = Expense::where('user_id', $userId)
            ->whereBetween('date', [$dateFrom, $dateTo]);

        // Применяем фильтры
        if ($carId) {
            $query->where('car_id', $carId);
        }
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Группировка для графика
        $mysqlFormat = $period === 'week' ? '%Y-%u' : ($period === 'year' ? '%Y' : '%Y-%m');
        
        // 🔧 Определяем драйвер БД для совместимости
        $dbDriver = DB::getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        
        if ($dbDriver === 'pgsql') {
            // PostgreSQL: используем TO_CHAR вместо DATE_FORMAT
            // Конвертируем форматы MySQL → PostgreSQL
            $pgFormat = str_replace(
                ['%Y-%m-%d', '%Y-%m', '%Y-%u', '%Y', '%m', '%d'],
                ['YYYY-MM-DD', 'YYYY-MM', 'YYYY-WW', 'YYYY', 'MM', 'DD'],
                $mysqlFormat
            );
            
            $chartData = $query->clone()
                ->selectRaw("TO_CHAR(date, '$pgFormat') as period, SUM(amount) as total")
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            // MySQL и другие: оставляем DATE_FORMAT
            $chartData = $query->clone()
                ->selectRaw("DATE_FORMAT(date, '$mysqlFormat') as period, SUM(amount) as total")
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }

        // Статистика
        $totalSum = $query->clone()->sum('amount') ?? 0;
        $totalCount = $query->clone()->count() ?? 0;
        
        $byCategory = $query->clone()
            ->select('category', DB::raw('SUM(amount) as total, COUNT(*) as count'))
            ->groupBy('category')
            ->get();

        // Данные для графиков
        $labels = $chartData->pluck('period')->toArray();
        $totals = $chartData->pluck('total')->map(fn($v) => (float)$v)->toArray();
        $pieLabels = $byCategory->pluck('category')->toArray();
        $pieValues = $byCategory->pluck('total')->map(fn($v) => (float)$v)->toArray();

        // ✅ Списки для фильтров
        $cars = Car::where('user_id', $userId)
            ->get()
            ->mapWithKeys(fn($car) => [$car->id => "$car->brand $car->model"]);
            
        $categories = [
            'fuel' => '⛽ Топливо',
            'wash' => '🚿 Мойка', 
            'repair' => '🔧 Ремонт',
            'maintenance' => '🛢️ ТО',
            'insurance' => '📄 Страховка',
            'other' => '📦 Прочее'
        ];

        return view('reports.index', compact(
            'period', 'carId', 'category', 'dateFrom', 'dateTo',
            'totalSum', 'totalCount', 'byCategory',
            'labels', 'totals', 'pieLabels', 'pieValues',
            'cars', 'categories'
        ));
    }

    public function export(Request $request)
    {
        $userId = auth()->id();
        $dateFrom = $request->input('date_from', now()->subYear()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $carId = $request->input('car_id');
        $category = $request->input('category');

        $query = Expense::where('user_id', $userId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->with('car:id,brand,model');

        if ($carId) $query->where('car_id', $carId);
        if ($category && $category !== 'all') $query->where('category', $category);

        $expenses = $query->orderBy('date', 'desc')->get();

        // Генерация CSV
        $filename = "expenses_{$dateFrom}_{$dateTo}.csv";
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            // BOM для корректного отображения кириллицы в Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Заголовки
            fputcsv($file, ['Дата', 'Автомобиль', 'Категория', 'Описание', 'Сумма (₽)'], ';');
            
            // Данные
            foreach ($expenses as $exp) {
                fputcsv($file, [
                    $exp->date->format('d.m.Y'),
                    $exp->car ? "{$exp->car->brand} {$exp->car->model}" : '—',
                    $exp->category,
                    $exp->description ?? '',
                    number_format($exp->amount, 2, ',', ' ')
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}