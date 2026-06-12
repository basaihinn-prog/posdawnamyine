<?php

namespace Database\Seeders;

use App\Models\DueCollect;
use Illuminate\Database\Seeder;

class DueCollectSeeder extends Seeder
{
    public function run(): void
    {
        $due_collects = array(
            array('business_id' => '1', 'party_id' => '6', 'user_id' => '4', 'payment_type_id' => '2', 'purchase_id' => NULL, 'sale_id' => NULL, 'invoiceNumber' => '#1', 'totalDue' => '24400.00', 'dueAmountAfterPay' => '17400.00', 'payDueAmount' => '7000.00', 'paymentDate' => '2025-09-11 10:17:19', 'created_at' => '2025-09-11 10:17:19', 'updated_at' => '2025-09-11 10:17:19'),
            array('business_id' => '1', 'party_id' => '4', 'user_id' => '4', 'payment_type_id' => '2', 'purchase_id' => NULL, 'sale_id' => '4', 'invoiceNumber' => '#2', 'totalDue' => '605.00', 'dueAmountAfterPay' => '105.00', 'payDueAmount' => '500.00', 'paymentDate' => '2025-09-11 10:17:44', 'created_at' => '2025-09-11 10:17:44', 'updated_at' => '2025-09-11 10:17:44'),
            array('business_id' => '1', 'party_id' => '2', 'user_id' => '4', 'payment_type_id' => '3', 'purchase_id' => NULL, 'sale_id' => '3', 'invoiceNumber' => '#3', 'totalDue' => '1353.90', 'dueAmountAfterPay' => '603.90', 'payDueAmount' => '750.00', 'paymentDate' => '2025-09-11 10:18:05', 'created_at' => '2025-09-11 10:18:05', 'updated_at' => '2025-09-11 10:18:05'),
            array('business_id' => '1', 'party_id' => NULL, 'user_id' => '4', 'payment_type_id' => '5', 'purchase_id' => NULL, 'sale_id' => '7', 'invoiceNumber' => '#4', 'totalDue' => '843.00', 'dueAmountAfterPay' => '413.00', 'payDueAmount' => '430.00', 'paymentDate' => '2025-09-11 10:18:28', 'created_at' => '2025-09-11 10:18:28', 'updated_at' => '2025-09-11 10:18:28'),
            array('business_id' => '1', 'party_id' => NULL, 'user_id' => '4', 'payment_type_id' => '4', 'purchase_id' => NULL, 'sale_id' => '6', 'invoiceNumber' => '#5', 'totalDue' => '989.00', 'dueAmountAfterPay' => '256.00', 'payDueAmount' => '733.00', 'paymentDate' => '2025-09-11 10:18:49', 'created_at' => '2025-09-11 10:18:49', 'updated_at' => '2025-09-11 10:18:49'),
            array('business_id' => '1', 'party_id' => NULL, 'user_id' => '4', 'payment_type_id' => '2', 'purchase_id' => NULL, 'sale_id' => '5', 'invoiceNumber' => '#6', 'totalDue' => '390.00', 'dueAmountAfterPay' => '300.00', 'payDueAmount' => '90.00', 'paymentDate' => '2025-09-11 10:18:59', 'created_at' => '2025-09-11 10:18:59', 'updated_at' => '2025-09-11 10:18:59')
        );

        DueCollect::insert($due_collects);
    }
}
