<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = array(
            array('business_id' => '1', 'expense_category_id' => '5', 'user_id' => '4', 'payment_type_id' => '1', 'amount' => '700.00', 'expanseFor' => 'Wifi and Electric Bill', 'referenceNo' => 'UT-6732', 'note' => 'I paid electric, water and internet bill', 'expenseDate' => '2025-09-09 00:00:00', 'created_at' => '2025-09-09 16:49:23', 'updated_at' => '2025-09-09 16:49:23'),
            array('business_id' => '1', 'expense_category_id' => '1', 'user_id' => '4', 'payment_type_id' => '2', 'amount' => '25000.00', 'expanseFor' => 'Monthly Office Rent', 'referenceNo' => 'RN-0923', 'note' => 'Rent paid for September', 'expenseDate' => '2025-09-01 00:00:00', 'created_at' => '2025-09-09 16:50:00', 'updated_at' => '2025-09-09 16:50:00'),
            array('business_id' => '1', 'expense_category_id' => '2', 'user_id' => '4', 'payment_type_id' => '3', 'amount' => '5000.00', 'expanseFor' => 'Facebook Ads', 'referenceNo' => 'MT-4551', 'note' => 'Social media marketing for new product', 'expenseDate' => '2025-09-05 00:00:00', 'created_at' => '2025-09-09 16:51:12', 'updated_at' => '2025-09-09 16:51:12'),
            array('business_id' => '1', 'expense_category_id' => '3', 'user_id' => '4', 'payment_type_id' => '4', 'amount' => '12000.00', 'expanseFor' => 'Catering for Event', 'referenceNo' => 'EV-8821', 'note' => 'Catering expenses for corporate event', 'expenseDate' => '2025-09-07 00:00:00', 'created_at' => '2025-09-09 16:52:25', 'updated_at' => '2025-09-09 16:52:25'),
            array('business_id' => '1', 'expense_category_id' => '4', 'user_id' => '4', 'payment_type_id' => '5', 'amount' => '8000.00', 'expanseFor' => 'Staff Salary','referenceNo' => 'SA-7893','note' => 'Monthly salary for staff','expenseDate' => '2025-09-08 00:00:00','created_at' => '2025-09-09 16:53:40','updated_at' => '2025-09-09 16:53:40'),
            array('business_id' => '1', 'expense_category_id' => '6', 'user_id' => '4', 'payment_type_id' => '1', 'amount' => '3000.00', 'expanseFor' => 'Miscellaneous','referenceNo' => 'OT-3342','note' => 'Office stationery and supplies','expenseDate' => '2025-09-08 00:00:00','created_at' => '2025-09-09 16:54:55','updated_at' => '2025-09-09 16:54:55')
        );

        Expense::insert($expenses);
    }
}
