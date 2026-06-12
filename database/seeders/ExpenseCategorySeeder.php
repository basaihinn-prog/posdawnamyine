<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $expense_categories = array(
            array('categoryName' => 'Rent', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:41:42', 'updated_at' => '2025-09-09 16:44:08'),
            array('categoryName' => 'Marketing', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:41:52', 'updated_at' => '2025-09-09 16:43:51'),
            array('categoryName' => 'Event Catering', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:42:02', 'updated_at' => '2025-09-09 16:42:02'),
            array('categoryName' => 'Staffs', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:44:24', 'updated_at' => '2025-09-09 16:44:24'),
            array('categoryName' => 'Utilities', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:44:34', 'updated_at' => '2025-09-09 16:44:34'),
            array('categoryName' => 'Others', 'business_id' => '1', 'status' => '1', 'created_at' => '2025-09-09 16:44:42', 'updated_at' => '2025-09-09 16:44:42')
        );

        ExpenseCategory::insert($expense_categories);
    }
}
