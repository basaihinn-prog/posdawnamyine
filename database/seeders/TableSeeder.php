<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{

    public function run(): void
    {
        $tables = array(
            array('business_id' => '1', 'name' => 'Table 1', 'capacity' => '6', 'is_booked' => '0', 'created_at' => '2025-04-08 10:55:13', 'updated_at' => '2025-09-10 09:20:02'),
            array('business_id' => '1', 'name' => 'Table 2', 'capacity' => '6', 'is_booked' => '1', 'created_at' => '2025-04-08 10:54:53', 'updated_at' => '2025-09-10 09:20:16'),
            array('business_id' => '1', 'name' => 'Table 3', 'capacity' => '6', 'is_booked' => '0', 'created_at' => '2025-09-10 09:21:53', 'updated_at' => '2025-09-10 09:21:53'),
            array('business_id' => '1', 'name' => 'Table 4', 'capacity' => '6', 'is_booked' => '1', 'created_at' => '2025-09-10 09:21:53', 'updated_at' => '2025-09-10 09:21:53'),
            array('business_id' => '1', 'name' => 'Table 5', 'capacity' => '6', 'is_booked' => '1', 'created_at' => '2025-09-10 09:21:53', 'updated_at' => '2025-09-11 09:23:49'),
            array('business_id' => '1', 'name' => 'VIP Table', 'capacity' => '8', 'is_booked' => '1', 'created_at' => '2025-09-10 09:20:36', 'updated_at' => '2025-09-11 09:18:33'),
            array('business_id' => '1', 'name' => 'Family Table', 'capacity' => '10', 'is_booked' => '0', 'created_at' => '2025-09-10 09:20:50', 'updated_at' => '2025-09-10 09:20:50'),
            array('business_id' => '1', 'name' => 'Corner Table', 'capacity' => '2', 'is_booked' => '1', 'created_at' => '2025-09-10 09:21:04', 'updated_at' => '2025-09-10 09:21:04'),
            array('business_id' => '1', 'name' => 'Window Table', 'capacity' => '4', 'is_booked' => '0', 'created_at' => '2025-09-10 09:21:22', 'updated_at' => '2025-09-10 09:21:22'),
            array('business_id' => '1', 'name' => 'Outdoor Table', 'capacity' => '12', 'is_booked' => '1', 'created_at' => '2025-09-10 09:21:32', 'updated_at' => '2025-09-10 09:21:32')
        );

        Table::insert($tables);
    }
}
