<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@lelite.com',
                'password' => Hash::make('Rest2026admin'),
                'role' => 'admin',
                'station' => null,
            ],
            [
                'name' => 'Kitchen Staff',
                'email' => 'kitchen@lelite.com',
                'password' => Hash::make('Rest2026kitchen'),
                'role' => 'station',
                'station' => 'kitchen',
            ],
            [
                'name' => 'Bar Staff',
                'email' => 'bar@lelite.com',
                'password' => Hash::make('Rest2026bar'),
                'role' => 'station',
                'station' => 'bar',
            ],
            [
                'name' => 'Shisha Staff',
                'email' => 'shisha@lelite.com',
                'password' => Hash::make('Rest2026shisha'),
                'role' => 'station',
                'station' => 'shisha',
            ],
            [
                'name' => 'Cash Staff',
                'email' => 'cash@lelite.com',
                'password' => Hash::make('Rest2026cash'),
                'role' => 'station',
                'station' => 'cash',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
