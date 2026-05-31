<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Car;
use App\Http\Requests\StoreReminderRequest;
use App\Services\MileageReminderChecker;

class ReminderController extends Controller
{
public function index()
{
    $userId = auth()->id();

    // Все активные напоминания
    $active = Reminder::where('user_id', $userId)
        ->where('status', 'active')
        ->with(['car:id,brand,model'])
        ->orderBy('due_date')
        ->get();

    // Выполненные (последние 5)
    $done = Reminder::where('user_id', $userId)
        ->where('status', 'done')
        ->with(['car:id,brand,model'])
        ->latest('updated_at')
        ->take(5)
        ->get();

    // Группировка по типам
    $groups = [
        'oil'         => $active->where('type', 'oil'),
        'coolant'     => $active->where('type', 'coolant'),
        'brake_fluid' => $active->where('type', 'brake_fluid'),
        'tires'       => $active->where('type', 'tires'),
        'inspection'  => $active->where('type', 'inspection'),
        'custom'      => $active->where('type', 'custom'),
        'done'        => $done,
    ];

    return view('reminders.index', compact('groups'));
}

    public function create()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        return view('reminders.create', compact('cars'));
    }

public function store(StoreReminderRequest $request, MileageReminderChecker $checker)
{
    $data = $request->validated();
    
    // Если включено "по пробегу" → считаем следующий порог
    if ($data['is_mileage_based'] && $data['car_id'] && $data['mileage_interval']) {
        $car = \App\Models\Car::find($data['car_id']);
        $data['next_mileage_due'] = $car->mileage + $data['mileage_interval'];
    }

    auth()->user()->reminders()->create($data);

    $back = $request->boolean('redirect_back') ? back() : redirect()->route('reminders.index');
    return $back->with('success', 'Напоминание создано');
}

public function markDone(Reminder $reminder)
{
    if ($reminder->user_id !== auth()->id()) abort(403);

    $reminder->update(['status' => 'done']);

    // 🔁 Если повторяющееся → генерируем следующее
    if ($reminder->is_recurring) {
        // 📅 Берём дату СТАРОГО напоминания как точку отсчёта
        $baseDate = \Carbon\Carbon::parse($reminder->due_date);
        
        $newDue = match($reminder->type) {
            'oil'         => $baseDate->copy()->addMonths(6),
            'coolant'     => $baseDate->copy()->addMonths(12),
            'brake_fluid' => $baseDate->copy()->addMonths(24),
            'tires'       => $baseDate->copy()->addMonths(6),
            'inspection'  => $baseDate->copy()->addYear(),
            'custom'      => $baseDate->copy()->addMonths(3),
            default       => $baseDate->copy()->addMonths(3),
        };

        // 🚗 Если напоминание привязано к пробегу — считаем следующий порог
        $nextMileage = null;
        if ($reminder->is_mileage_based && $reminder->car_id && $reminder->mileage_interval) {
            $car = \App\Models\Car::find($reminder->car_id);
            $nextMileage = ($car?->mileage ?? 0) + $reminder->mileage_interval;
        }

        // 🆕 Создаём копию с новым сроком (и сохраняем настройки пробега!)
        $reminder->replicate()->fill([
            'status'           => 'active',
            'due_date'         => $newDue,
            'is_mileage_based' => $reminder->is_mileage_based, // ← сохраняем галочку!
            'mileage_interval' => $reminder->mileage_interval,  // ← сохраняем интервал!
            'next_mileage_due' => $nextMileage,                 // ← новая цель по пробегу
        ])->save();
    }

    return back()->with('success', '✅ Выполнено! Следующее напоминание: ' . $newDue->format('d.m.Y'));
}

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) abort(403);
        $reminder->delete();
        return back()->with('success', 'Напоминание удалено');
    }
}