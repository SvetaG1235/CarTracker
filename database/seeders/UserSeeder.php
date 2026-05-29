<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 👑 Администратор
        User::firstOrCreate(
            ['email' => 'admin@cartracker.test'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 👤 Демо-пользователь (на его данные будут привязаны машины и расходы)
        User::firstOrCreate(
            ['email' => 'user@cartracker.test'],
            [
                'name' => 'Иван Иванов',
                'password' => Hash::make('user'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}