<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = array(
            array('business_id' => '1', 'name' => 'Fast Food', 'created_at' => '2025-03-15 12:15:53', 'updated_at' => '2025-09-10 13:11:02'),
            array('business_id' => '1', 'name' => 'Snacks', 'created_at' => '2025-03-15 12:16:04', 'updated_at' => '2025-09-10 13:06:02'),
            array('business_id' => '1', 'name' => 'Salads', 'created_at' => '2025-03-15 12:16:26', 'updated_at' => '2025-09-10 13:10:01'),
            array('business_id' => '1', 'name' => 'Main Course', 'created_at' => '2025-03-15 12:16:35', 'updated_at' => '2025-09-10 13:04:26'),
            array('business_id' => '1', 'name' => 'Desserts', 'created_at' => '2025-09-10 09:32:19', 'updated_at' => '2025-09-10 13:09:44'),
            array('business_id' => '1', 'name' => 'Beverages', 'created_at' => '2025-09-10 09:32:32', 'updated_at' => '2025-09-10 13:10:29')
        );

        Menu::insert($menus);
    }
}
