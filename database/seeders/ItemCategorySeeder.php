<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Starters and small bites',
                'items' => [
                    ['name' => 'Bruschetta', 'price' => 8.99, 'preparation_time' => 10],
                    ['name' => 'Caesar Salad', 'price' => 12.99, 'preparation_time' => 8],
                    ['name' => 'Spring Rolls', 'price' => 7.50, 'preparation_time' => 12],
                ]
            ],
            [
                'name' => 'Main Course',
                'description' => 'Entrees and main dishes',
                'items' => [
                    ['name' => 'Grilled Salmon', 'price' => 24.99, 'preparation_time' => 20],
                    ['name' => 'Ribeye Steak', 'price' => 32.99, 'preparation_time' => 25],
                    ['name' => 'Pasta Primavera', 'price' => 18.99, 'preparation_time' => 15],
                    ['name' => 'Chicken Alfredo', 'price' => 19.99, 'preparation_time' => 18],
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats',
                'items' => [
                    ['name' => 'Tiramisu', 'price' => 9.99, 'preparation_time' => 5],
                    ['name' => 'Chocolate Cake', 'price' => 8.99, 'preparation_time' => 5],
                    ['name' => 'Cheesecake', 'price' => 10.99, 'preparation_time' => 5],
                ]
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks and refreshments',
                'items' => [
                    ['name' => 'Fresh Orange Juice', 'price' => 5.99, 'preparation_time' => 3],
                    ['name' => 'Iced Coffee', 'price' => 4.99, 'preparation_time' => 2],
                    ['name' => 'Sparkling Water', 'price' => 3.99, 'preparation_time' => 1],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);
            
            $category = ItemCategory::create($categoryData);
            
            foreach ($items as $item) {
                $item['category_id'] = $category->id;
                $item['is_available'] = true;
                Item::create($item);
            }
        }
    }
}
