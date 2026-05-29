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
        if (!$user) return;

        $cars = $user->cars;
        $categories = ['fuel', 'wash', 'repair', 'maintenance', 'insurance'];
        $descriptions = [
            'fuel' => ['Лукойл 95', 'Газпромнефть 100', 'Shell V-Power', 'АИ-92 заправка'],
            'wash' => ['Мойка кузова', 'Химчистка салона', 'Мойка колес'],
            'repair' => ['Замена колодок', 'Замена масла', 'Ремонт ходовой', 'Замена ламп'],
            'maintenance' => ['ТО-1', 'ТО-2', 'Замена фильтров'],
            'insurance' => ['ОСАГО', 'Каско']
        ];

        // Генерируем расходы за последние 12 месяцев
        for ($i = 0; $i < 150; $i++) {
            $randomCar = $cars->random();
            $category = $categories[array_rand($categories)];
            
            // Случайная дата за последний год
            $date = Carbon::now()->subDays(rand(0, 365))->format('Y-m-d');
            
            // Цена зависит от категории
            $amount = match($category) {
                'fuel' => rand(1500, 4000),
                'wash' => rand(300, 3000),
                'repair' => rand(1000, 15000),
                'maintenance' => rand(5000, 25000),
                'insurance' => rand(5000, 40000),
                default => rand(500, 2000)
            };

            $description = $descriptions[$category][array_rand($descriptions[$category])];

            $randomCar->expenses()->create([
                'user_id' => $user->id,
                'category' => $category,
                'amount' => $amount,
                'date' => $date,
                'description' => $description,
            ]);
        }
    }
}