<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = array(
            array('business_id' => '1', 'sale_id' => NULL, 'purchase_id' => '1', 'payment_type_id' => '1', 'invoiceNumber' => 'T-00001', 'date' => '2025-09-10 00:00:00', 'total_amount' => '14400.00', 'paid_amount' => '7000.00', 'due_amount' => '7400.00', 'type' => 'debit', 'created_at' => '2025-09-10 16:17:20', 'updated_at' => '2025-09-10 16:17:20'),
            array('business_id' => '1', 'sale_id' => NULL, 'purchase_id' => '2', 'payment_type_id' => '2', 'invoiceNumber' => 'T-00002', 'date' => '2025-09-10 00:00:00', 'total_amount' => '1185.00', 'paid_amount' => '1185.00', 'due_amount' => '0.00', 'type' => 'debit', 'created_at' => '2025-09-10 16:20:07', 'updated_at' => '2025-09-10 16:20:07'),
            array('business_id' => '1', 'sale_id' => NULL, 'purchase_id' => '3', 'payment_type_id' => NuLL, 'invoiceNumber' => 'T-00003', 'date' => '2025-09-10 00:00:00', 'total_amount' => '940.00', 'paid_amount' => '0.00', 'due_amount' => '940.00', 'type' => 'debit', 'created_at' => '2025-09-10 16:25:46', 'updated_at' => '2025-09-10 16:53:31'),
            array('business_id' => '1', 'sale_id' => NULL, 'purchase_id' => '4', 'payment_type_id' => NuLL, 'invoiceNumber' => 'T-00004', 'date' => '2025-09-10 00:00:00', 'total_amount' => '1117.00', 'paid_amount' => '0.00', 'due_amount' => '1117.00', 'type' => 'debit', 'created_at' => '2025-09-10 17:03:41', 'updated_at' => '2025-09-10 17:03:41'),
            array('business_id' => '1', 'sale_id' => '1', 'purchase_id' => NULL, 'payment_type_id' => '1', 'invoiceNumber' => 'T-00005', 'date' => '2025-09-11 09:18:33', 'total_amount' => '534.50', 'paid_amount' => '200.00', 'due_amount' => '334.50', 'type' => 'credit', 'created_at' => '2025-09-11 09:18:33', 'updated_at' => '2025-09-11 09:18:33'),
            array('business_id' => '1', 'sale_id' => '2', 'purchase_id' => NULL, 'payment_type_id' => '3', 'invoiceNumber' => 'T-00006', 'date' => '2025-09-11 09:20:17', 'total_amount' => '902.50', 'paid_amount' => '902.50', 'due_amount' => '0.00', 'type' => 'credit', 'created_at' => '2025-09-11 09:20:17', 'updated_at' => '2025-09-11 09:20:17'),
            array('business_id' => '1', 'sale_id' => '3', 'purchase_id' => NULL, 'payment_type_id' => NuLL, 'invoiceNumber' => 'T-00007', 'date' => '2025-09-11 09:22:11', 'total_amount' => '1353.90', 'paid_amount' => '0.00', 'due_amount' => '1353.90', 'type' => 'credit', 'created_at' => '2025-09-11 09:22:11', 'updated_at' => '2025-09-11 09:22:11'),
            array('business_id' => '1', 'sale_id' => '4', 'purchase_id' => NULL, 'payment_type_id' => NuLL, 'invoiceNumber' => 'T-00008', 'date' => '2025-09-11 09:23:49', 'total_amount' => '605.00', 'paid_amount' => '0.00', 'due_amount' => '605.00', 'type' => 'credit', 'created_at' => '2025-09-11 09:23:49', 'updated_at' => '2025-09-11 09:23:49'),
            array('business_id' => '1', 'sale_id' => '5', 'purchase_id' => NULL, 'payment_type_id' => '2', 'invoiceNumber' => 'T-00009', 'date' => '2025-09-11 09:25:03', 'total_amount' => '730.00', 'paid_amount' => '340.00', 'due_amount' => '390.00', 'type' => 'credit', 'created_at' => '2025-09-11 09:25:03', 'updated_at' => '2025-09-11 09:25:03'),
            array('business_id' => '1', 'sale_id' => '6', 'purchase_id' => NULL, 'payment_type_id' => NuLL, 'invoiceNumber' => 'T-00010', 'date' => '2025-09-11 09:25:56', 'total_amount' => '989.00', 'paid_amount' => '0.00', 'due_amount' => '989.00', 'type' => 'credit', 'created_at' => '2025-09-11 09:25:56', 'updated_at' => '2025-09-11 09:25:56'),
            array('business_id' => '1', 'sale_id' => '7', 'purchase_id' => NULL, 'payment_type_id' => '2', 'invoiceNumber' => 'T-00011', 'date' => '2025-09-11 09:27:38', 'total_amount' => '1023.00', 'paid_amount' => '180.00', 'due_amount' => '843.00', 'type' => 'credit', 'created_at' => '2025-09-11 09:27:38', 'updated_at' => '2025-09-11 09:27:38')
        );

        Transaction::insert($transactions);
    }
}
