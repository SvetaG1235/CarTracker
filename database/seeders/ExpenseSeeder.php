<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'user@cartracker.test')->first();
        if (!$user) {
            echo "❌ Пользователь user@cartracker.test не найден. Сначала запустите CarSeeder.\n";
            return;
        }

        $cars = $user->cars;
        if ($cars->isEmpty()) {
            echo "⚠️ У пользователя нет автомобилей. Сначала запустите CarSeeder.\n";
            return;
        }

        // Категории и описания
        $categories = ['fuel', 'wash', 'repair', 'maintenance', 'insurance', 'other'];
        $descriptions = [
            'fuel' => ['Лукойл 95', 'Газпромнефть 100', 'Shell V-Power', 'АИ-92 заправка', 'Роснефть АИ-95'],
            'wash' => ['Мойка кузова', 'Химчистка салона', 'Мойка колес', 'Комплексная мойка'],
            'repair' => ['Замена колодок', 'Замена масла', 'Ремонт ходовой', 'Замена ламп', 'Диагностика'],
            'maintenance' => ['ТО-1', 'ТО-2', 'Замена фильтров', 'Замена ремня ГРМ'],
            'insurance' => ['ОСАГО', 'Каско', 'Расширенная страховка'],
            'other' => ['Штраф ГИБДД', 'Парковка', 'Аксессуары', 'Шиномонтаж']
        ];

        // Генерируем 150-200 расходов
        $totalExpenses = rand(150, 200);
        
        for ($i = 0; $i < $totalExpenses; $i++) {
            $randomCar = $cars->random();
            $category = $categories[array_rand($categories)];
            
            // Случайная дата за последние 12 месяцев
            $date = Carbon::now()->subDays(rand(0, 365))->format('Y-m-d');
            
            // Цена зависит от категории (реалистичные диапазоны)
            $amount = match($category) {
                'fuel' => rand(1500, 4500),
                'wash' => rand(300, 2500),
                'repair' => rand(1000, 18000),
                'maintenance' => rand(4000, 30000),
                'insurance' => rand(5000, 50000),
                default => rand(200, 3000)
            };

            $descriptionList = $descriptions[$category] ?? ['Расход'];
            $description = $descriptionList[array_rand($descriptionList)];

            $randomCar->expenses()->create([
                'user_id' => $user->id,
                'category' => $category,
                'amount' => $amount,
                'date' => $date,
                'description' => $description,
            ]);
        }
        
        echo "✅ Создано ~{$totalExpenses} расходов для " . $cars->count() . " автомобилей\n";
    }
}