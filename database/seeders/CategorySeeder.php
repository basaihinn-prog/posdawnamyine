<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = array(
            array('business_id' => '1', 'categoryName' => 'Juice', 'status' => '1', 'created_at' => '2025-03-15 11:59:26', 'updated_at' => '2025-03-15 11:59:26'),
            array('business_id' => '1', 'categoryName' => 'Pizza', 'status' => '1', 'created_at' => '2025-03-15 11:59:36', 'updated_at' => '2025-03-15 11:59:36'),
            array('business_id' => '1', 'categoryName' => 'Burger', 'status' => '1', 'created_at' => '2025-03-15 11:59:53', 'updated_at' => '2025-03-15 11:59:53'),
            array('business_id' => '1', 'categoryName' => 'Breakfast', 'status' => '1', 'created_at' => '2025-09-10 09:28:11', 'updated_at' => '2025-09-10 09:28:11'),
            array('business_id' => '1', 'categoryName' => 'Rice Dishes', 'status' => '1', 'created_at' => '2025-09-10 09:28:20', 'updated_at' => '2025-09-10 13:07:04'),
            array('business_id' => '1', 'categoryName' => 'Noodles', 'status' => '1', 'created_at' => '2025-09-10 09:28:40', 'updated_at' => '2025-09-10 13:08:20'),
            array('business_id' => '1', 'categoryName' => 'Kabab', 'status' => '1', 'created_at' => '2025-09-10 09:28:50', 'updated_at' => '2025-09-10 09:28:50'),
            array('business_id' => '1', 'categoryName' => 'Drinks', 'status' => '1', 'created_at' => '2025-09-10 09:30:09', 'updated_at' => '2025-09-10 09:30:09')
        );

        Category::insert($categories);
    }
}
