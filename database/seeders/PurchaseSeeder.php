<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $purchases = array(
            array('party_id' => '6', 'business_id' => '1', 'user_id' => '4', 'payment_type_id' => '1', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'tax_amount' => '0.00', 'tax_percentage' => '0.00', 'dueAmount' => '7400.00', 'paidAmount' => '7000.00', 'totalAmount' => '14400.00', 'invoiceNumber' => '3', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => '2025-09-10 16:17:20', 'updated_at' => '2025-09-10 16:17:20'),
            array('party_id' => '3', 'business_id' => '1', 'user_id' => '4', 'payment_type_id' => '2', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'tax_amount' => '0.00', 'tax_percentage' => '0.00', 'dueAmount' => '0.00', 'paidAmount' => '1185.00', 'totalAmount' => '1185.00', 'invoiceNumber' => '4', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => '2025-09-10 16:20:07', 'updated_at' => '2025-09-10 16:20:07'),
            array('party_id' => '1', 'business_id' => '1', 'user_id' => '4', 'payment_type_id' => NuLL, 'discountAmount' => '150.00', 'discountPercentage' => '14.71', 'tax_amount' => '70.00', 'tax_percentage' => '6.86', 'dueAmount' => '940.00', 'paidAmount' => '0.00', 'totalAmount' => '940.00', 'invoiceNumber' => '3', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => '2025-09-10 16:25:46', 'updated_at' => '2025-09-10 16:53:31'),
            array('party_id' => '3', 'business_id' => '1', 'user_id' => '4', 'payment_type_id' => NuLL, 'discountAmount' => '25.00', 'discountPercentage' => '2.21', 'tax_amount' => '12.00', 'tax_percentage' => '1.06', 'dueAmount' => '1117.00', 'paidAmount' => '0.00', 'totalAmount' => '1117.00', 'invoiceNumber' => '4', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => '2025-09-10 17:03:41', 'updated_at' => '2025-09-10 17:03:41')
        );

        Purchase::insert($purchases);

        $purchase_details = array(
            array('purchase_id' => '1', 'ingredient_id' => '1', 'unit_id' => '1', 'unit_price' => '500', 'quantities' => '20'),
            array('purchase_id' => '1', 'ingredient_id' => '3', 'unit_id' => '1', 'unit_price' => '60', 'quantities' => '40'),
            array('purchase_id' => '1', 'ingredient_id' => '5', 'unit_id' => '1', 'unit_price' => '200', 'quantities' => '10'),
            array('purchase_id' => '2', 'ingredient_id' => '4', 'unit_id' => '2', 'unit_price' => '75', 'quantities' => '7'),
            array('purchase_id' => '2', 'ingredient_id' => '12', 'unit_id' => '2', 'unit_price' => '5', 'quantities' => '100'),
            array('purchase_id' => '2', 'ingredient_id' => '13', 'unit_id' => '2', 'unit_price' => '0.2', 'quantities' => '800'),
            array('purchase_id' => '3', 'ingredient_id' => '8', 'unit_id' => '1', 'unit_price' => '20', 'quantities' => '5'),
            array('purchase_id' => '3', 'ingredient_id' => '9', 'unit_id' => '1', 'unit_price' => '140', 'quantities' => '3'),
            array('purchase_id' => '3', 'ingredient_id' => '7', 'unit_id' => '1', 'unit_price' => '50', 'quantities' => '10'),
            array('purchase_id' => '4', 'ingredient_id' => '10', 'unit_id' => '2', 'unit_price' => '0.4', 'quantities' => '950'),
            array('purchase_id' => '4', 'ingredient_id' => '11', 'unit_id' => '2', 'unit_price' => '0.3', 'quantities' => '2500')
        );

        PurchaseDetails::insert($purchase_details);
    }
}
