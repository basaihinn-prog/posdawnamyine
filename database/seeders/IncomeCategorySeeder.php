<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $income_categories = array(
            array('categoryName' => 'Food Sales', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:45:56', 'updated_at' => '2025-09-09 16:45:56'),
            array('categoryName' => 'Delivery', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:46:08', 'updated_at' => '2025-09-09 16:46:08'),
            array('categoryName' => 'Catering', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:46:17', 'updated_at' => '2025-09-09 16:46:17'),
            array('categoryName' => 'Events', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:46:28', 'updated_at' => '2025-09-09 16:46:28'),
            array('categoryName' => 'Others', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:46:35', 'updated_at' => '2025-09-09 16:46:35')
        );

        IncomeCategory::insert($income_categories);
    }
}
