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
        $newDue = match($reminder->type) {
            'oil'         => now()->addMonths(6),
            'coolant'     => now()->addMonths(12),
            'brake_fluid' => now()->addMonths(24),
            'tires'       => now()->addMonths(6),
            'inspection'  => now()->addYear(),
            'custom'      => now()->addMonths(3),
        };

        // Создаём копию с новым сроком
        $reminder->replicate()->fill([
            'status'       => 'active',
            'due_date'     => $newDue,
            'is_mileage_based' => false, // сбрасываем пробеговую привязку для нового цикла
            'next_mileage_due' => null,
        ])->save();
    }

    return back()->with('success', 'Отмечено как выполненное. Новое напоминание создано.');
}

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== auth()->id()) abort(403);
        $reminder->delete();
        return back()->with('success', 'Напоминание удалено');
    }
}