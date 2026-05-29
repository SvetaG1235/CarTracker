<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\User;

class CarSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'user@cartracker.test')->first();
        if (!$user) return;

        $cars = [
            ['brand' => 'Toyota', 'model' => 'Camry', 'year' => 2020, 'vin' => '4T1BF1FK5CU000001', 'plate' => 'А 111 АА 777', 'mileage' => 45000],
            ['brand' => 'BMW', 'model' => 'X5', 'year' => 2018, 'vin' => '5UXKR0C57J0000002', 'plate' => 'В 222 ВВ 77', 'mileage' => 82000],
            ['brand' => 'Lada', 'model' => 'Vesta', 'year' => 2022, 'vin' => 'XTA21108070000003', 'plate' => 'Е 333 ЕЕ 01', 'mileage' => 12000],
        ];

        foreach ($cars as $carData) {
            $user->cars()->firstOrCreate(['vin' => $carData['vin']], $carData);
        }
    }
}