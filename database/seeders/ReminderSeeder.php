<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class ReminderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'user@cartracker.test')->first();
        if (!$user) return;

        $car = $user->cars->first();
        if (!$car) return;

        // ✅ Разрешённые type: oil, coolant, brake_fluid, tires, inspection, custom
        $reminders = [
            [
                'title' => 'Замена масла в ДВС',
                'type' => 'oil',
                'due_date' => Carbon::now()->addDays(5), // Скоро!
                'is_recurring' => true,
                'status' => 'active',
                'car_id' => $car->id
            ],
            [
                'title' => 'Шиномонтаж (зима -> лето)',
                'type' => 'tires',
                'due_date' => Carbon::now()->addDays(45),
                'is_recurring' => true,
                'status' => 'active',
                'car_id' => $car->id
            ],
            [
                'title' => 'Проверка тормозной системы',
                'type' => 'brake_fluid',
                'due_date' => Carbon::now()->addDays(120),
                'is_recurring' => false,
                'status' => 'active',
                'car_id' => $car->id
            ],
            [
                'title' => 'Замена воздушного фильтра',
                'type' => 'custom', // 🔥 Исправлено: maintenance нет в ENUM таблицы
                'due_date' => Carbon::now()->subDays(10), // Просрочено
                'is_recurring' => false,
                'status' => 'active',
                'car_id' => $car->id
            ],
        ];

        foreach ($reminders as $data) {
            $user->reminders()->firstOrCreate(['title' => $data['title']], $data);
        }
    }
}