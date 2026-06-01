<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CarSeeder extends Seeder
{
    public function run()
    {
        // 1. Находим пользователя или создаем, если нет (чтобы сеeder работал с нуля)
        $user = User::firstOrCreate(
            ['email' => 'user@cartracker.test'],
            [
                'name' => 'Демо Пользователь',
                'password' => Hash::make('password')
            ]
        );

        // 2. Данные для 10 автомобилей
        $carsData = [
            ['brand' => 'Toyota', 'model' => 'Camry', 'year' => 2020, 'vin' => '4T1BF1FK5CU000001', 'plate' => 'А 111 АА 777', 'mileage' => 45000],
            ['brand' => 'BMW', 'model' => 'X5', 'year' => 2018, 'vin' => '5UXKR0C57J0000002', 'plate' => 'В 222 ВВ 77', 'mileage' => 82000],
            ['brand' => 'Lada', 'model' => 'Vesta', 'year' => 2022, 'vin' => 'XTA21108070000003', 'plate' => 'Е 333 ЕЕ 01', 'mileage' => 12000],
            ['brand' => 'Kia', 'model' => 'Rio', 'year' => 2021, 'vin' => 'XWEXXX111111111', 'plate' => 'К 444 КК 777', 'mileage' => 30000],
            ['brand' => 'Hyundai', 'model' => 'Solaris', 'year' => 2019, 'vin' => 'Z9EXXX222222222', 'plate' => 'Х 555 ХХ 50', 'mileage' => 65000],
            ['brand' => 'Mercedes', 'model' => 'C-Class', 'year' => 2023, 'vin' => 'WDDGF4HB1111111', 'plate' => 'М 666 ММ 777', 'mileage' => 5000],
            ['brand' => 'Volkswagen', 'model' => 'Polo', 'year' => 2020, 'vin' => 'XWEXXX333333333', 'plate' => 'Ф 777 ФФ 02', 'mileage' => 40000],
            ['brand' => 'Skoda', 'model' => 'Octavia', 'year' => 2017, 'vin' => 'TMBEXXX44444444', 'plate' => 'С 888 СС 99', 'mileage' => 110000],
            ['brand' => 'Mazda', 'model' => '3', 'year' => 2022, 'vin' => 'JMZBLXXX5555555', 'plate' => 'Р 999 РР 777', 'mileage' => 15000],
            ['brand' => 'Ford', 'model' => 'Focus', 'year' => 2015, 'vin' => 'XWEXXX666666666', 'plate' => 'О 000 ОО 777', 'mileage' => 150000],
        ];

        // Категории расходов для рандома
        $categories = ['fuel', 'wash', 'repair', 'maintenance', 'insurance', 'other'];
        
        foreach ($carsData as $carData) {
            // Создаем машину (привязываем к пользователю)
            $car = $user->cars()->firstOrCreate(
                ['vin' => $carData['vin']], 
                array_merge($carData, ['user_id' => $user->id])
            );

            // Генерируем 20 случайных расходов для каждой машины
            for ($i = 0; $i < 20; $i++) {
                $category = $categories[array_rand($categories)];
                $amount = fake()->randomFloat(2, 500, 25000); // Сумма от 500 до 25000
                
                Expense::create([
                    'user_id' => $user->id,
                    'car_id' => $car->id,
                    'date' => fake()->dateTimeBetween('-1 year', 'now'), // Случайная дата за последний год
                    'category' => $category,
                    'amount' => $amount,
                    'description' => fake()->sentence(3) // Случайное описание
                ]);
            }
        }
        
        echo "✅ Создано 10 авто и ~200 расходов для пользователя " . $user->name . "\n";
    }
}