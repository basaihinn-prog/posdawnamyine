<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $incomes = array(
            array('business_id' => '1', 'income_category_id' => '1', 'user_id' => '4', 'payment_type_id' => '4', 'amount' => '2300.00', 'incomeFor' => 'Offline Food Sales', 'referenceNo' => 'FS-8735', 'note' => 'Today\'s offline sale was very good', 'incomeDate' => '2025-09-09 00:00:00', 'created_at' => '2025-09-09 16:50:51', 'updated_at' => '2025-09-09 16:50:51'),
            array('business_id' => '1', 'income_category_id' => '2', 'user_id' => '4', 'payment_type_id' => '1', 'amount' => '1200.00', 'incomeFor' => 'Home Delivery', 'referenceNo' => 'DL-7812', 'note' => 'Delivered 25 orders today', 'incomeDate' => '2025-09-09 00:00:00', 'created_at' => '2025-09-09 16:52:15', 'updated_at' => '2025-09-09 16:52:15'),
            array('business_id' => '1', 'income_category_id' => '3', 'user_id' => '4', 'payment_type_id' => '2', 'amount' => '4500.00', 'incomeFor' => 'Catering Event', 'referenceNo' => 'CT-9941', 'note' => 'Catering for wedding event', 'incomeDate' => '2025-09-08 00:00:00', 'created_at' => '2025-09-09 16:53:30', 'updated_at' => '2025-09-09 16:53:30'),
            array('business_id' => '1', 'income_category_id' => '4', 'user_id' => '4', 'payment_type_id' => '5', 'amount' => '6000.00', 'incomeFor' => 'Corporate Event', 'referenceNo' => 'EV-3321', 'note' => 'Income from corporate event booking', 'incomeDate' => '2025-09-07 00:00:00', 'created_at' => '2025-09-09 16:54:45', 'updated_at' => '2025-09-09 16:54:45'),
            array('business_id' => '1', 'income_category_id' => '5', 'user_id' => '4', 'payment_type_id' => '3', 'amount' => '900.00', 'incomeFor' => 'Miscellaneous Income', 'referenceNo' => 'OT-5567', 'note' => 'Miscellaneous income from snacks', 'incomeDate' => '2025-09-09 00:00:00', 'created_at' => '2025-09-09 16:55:55', 'updated_at' => '2025-09-09 16:55:55')
        );

        Income::insert($incomes);
    }
}
