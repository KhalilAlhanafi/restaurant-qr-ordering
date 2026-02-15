<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantTableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T01', 'capacity' => 2, 'location' => 'indoor'],
            ['table_number' => 'T02', 'capacity' => 2, 'location' => 'indoor'],
            ['table_number' => 'T03', 'capacity' => 4, 'location' => 'indoor'],
            ['table_number' => 'T04', 'capacity' => 4, 'location' => 'indoor'],
            ['table_number' => 'T05', 'capacity' => 6, 'location' => 'indoor'],
            ['table_number' => 'T06', 'capacity' => 6, 'location' => 'outdoor'],
            ['table_number' => 'T07', 'capacity' => 8, 'location' => 'outdoor'],
            ['table_number' => 'T08', 'capacity' => 2, 'location' => 'balcony'],
        ];

        foreach ($tables as $table) {
            $table['qr_token'] = Str::random(16);
            $table['status'] = 'available';
            RestaurantTable::create($table);
        }
    }
}
