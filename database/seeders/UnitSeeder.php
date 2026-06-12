<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = array(
            array('unitName' => 'Kg', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:05:20', 'updated_at' => '2025-03-15 12:05:20'),
            array('unitName' => 'Gram', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:05:20', 'updated_at' => '2025-03-15 12:05:20'),
            array('unitName' => 'Pcs', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:05:34', 'updated_at' => '2025-03-15 12:05:34'),
            array('unitName' => 'Bottle', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:05:46', 'updated_at' => '2025-03-15 12:05:46'),
            array('unitName' => 'Plate', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:05:56', 'updated_at' => '2025-03-15 12:05:56'),
            array('unitName' => 'Glass', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:06:05', 'updated_at' => '2025-03-15 12:06:05'),
            array('unitName' => 'Cup', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-03-15 12:06:05', 'updated_at' => '2025-03-15 12:06:05'),
        );

        Unit::insert($units);
    }
}
