<?php

namespace Database\Seeders;

use App\Models\KotTicket;
use App\Models\Sale;
use App\Models\SaleDetailOption;
use App\Models\SaleDetails;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $kot_tickets = array(
            array('business_id' => '1', 'table_id' => '5', 'cancel_reason_id' => NULL, 'kot_number' => '#1', 'bill_no' => '#4', 'status' => 'pending', 'notes' => NULL, 'created_at' => '2025-09-11 09:23:49', 'updated_at' => '2025-09-11 09:23:49'),
            array('business_id' => '1', 'table_id' => '3', 'cancel_reason_id' => NULL, 'kot_number' => '#2', 'bill_no' => '#5', 'status' => 'preparing', 'notes' => NULL, 'created_at' => '2026-01-21 13:07:08', 'updated_at' => '2026-01-21 13:07:08'),
            array('business_id' => '1', 'table_id' => '1', 'cancel_reason_id' => '2', 'kot_number' => '#3', 'bill_no' => '#7', 'status' => 'cancelled', 'notes' => NULL, 'created_at' => '2026-01-21 13:07:08', 'updated_at' => '2026-01-21 13:07:08')
        );

        KotTicket::insert($kot_tickets);

        $sales = array(
            array('business_id' => '1', 'party_id' => '5', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => '2', 'coupon_id' => NULL, 'payment_type_id' => '1', 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '20.00', 'discountPercentage' => '4.04', 'discount_type' => NULL, 'tax_amount' => '49.50', 'dueAmount' => '334.50', 'paidAmount' => '200.00', 'totalAmount' => '534.50', 'invoiceNumber' => '#1', 'sales_type' => 'dine_in', 'saleDate' => '2025-09-11 09:18:33', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":"10","delivery_charge":0,"payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:18:33', 'updated_at' => '2025-09-11 09:18:33'),
            array('business_id' => '1', 'party_id' => NULL, 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => NULL, 'coupon_id' => '3', 'payment_type_id' => '3', 'billing_address_id' => NULL, 'coupon_amount' => '1202.5', 'coupon_percentage' => '65', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'discount_type' => NULL, 'tax_amount' => '185.00', 'dueAmount' => '0.00', 'paidAmount' => '902.50', 'totalAmount' => '902.50', 'invoiceNumber' => '#2', 'sales_type' => 'pick_up', 'saleDate' => '2025-09-11 09:20:17', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":"70","delivery_charge":0,"payment_method":"fullPayment"}', 'created_at' => '2025-09-11 09:20:17', 'updated_at' => '2025-09-11 09:20:17'),
            array('business_id' => '1', 'party_id' => '2', 'address_id' => '2', 'user_id' => '4', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => NULL, 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '50.00', 'discountPercentage' => '4.35', 'discount_type' => NULL, 'tax_amount' => '114.90', 'dueAmount' => '603.90', 'paidAmount' => '0.00', 'totalAmount' => '1353.90', 'invoiceNumber' => '#3', 'sales_type' => 'delivery', 'saleDate' => '2025-09-11 09:22:11', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":"20","delivery_charge":"120","payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:22:11', 'updated_at' => '2025-09-11 10:18:05'),
            array('business_id' => '1', 'party_id' => '4', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => '1', 'table_id' => '1', 'staff_id' => '3', 'coupon_id' => NULL, 'payment_type_id' => NULL, 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'discount_type' => NULL, 'tax_amount' => '55.00', 'dueAmount' => '105.00', 'paidAmount' => '0.00', 'totalAmount' => '605.00', 'invoiceNumber' => '#4', 'sales_type' => 'dine_in', 'saleDate' => '2025-09-11 09:23:49', 'status' => 'pending', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":0,"delivery_charge":0,"payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:23:49', 'updated_at' => '2025-09-11 10:17:44'),
            array('business_id' => '1', 'party_id' => '3', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => '2', 'table_id' => '2', 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => '2', 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '25.00', 'discountPercentage' => '3.68', 'discount_type' => NULL, 'tax_amount' => '68.00', 'dueAmount' => '300.00', 'paidAmount' => '340.00', 'totalAmount' => '730.00', 'invoiceNumber' => '#5', 'sales_type' => 'dine_in', 'saleDate' => '2025-09-11 09:25:03', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":"7","delivery_charge":0,"payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:25:03', 'updated_at' => '2025-09-11 10:18:59'),
            array('business_id' => '1', 'party_id' => NULL, 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => NULL, 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'discount_type' => NULL, 'tax_amount' => '82.00', 'dueAmount' => '256.00', 'paidAmount' => '0.00', 'totalAmount' => '989.00', 'invoiceNumber' => '#6', 'sales_type' => 'delivery', 'saleDate' => '2025-09-11 09:25:56', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":"7","delivery_charge":"80","payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:25:56', 'updated_at' => '2025-09-11 10:18:49'),
            array('business_id' => '1', 'party_id' => '2', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'kot_id' => '3', 'table_id' => '3', 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => '2', 'billing_address_id' => NULL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'discount_type' => NULL, 'tax_amount' => '93.00', 'dueAmount' => '413.00', 'paidAmount' => '180.00', 'totalAmount' => '1023.00', 'invoiceNumber' => '#7', 'sales_type' => 'dine_in', 'saleDate' => '2025-09-11 09:27:38', 'status' => 'completed', 'order_status' => NULL, 'sale_data' => NULL, 'meta' => '{"tip":0,"delivery_charge":0,"payment_method":"duePayment"}', 'created_at' => '2025-09-11 09:27:38', 'updated_at' => '2025-09-11 10:18:28'),
            array('business_id' => '1', 'party_id' => NULL, 'address_id' => NULL, 'user_id' => '7', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => NULL, 'billing_address_id' => '1', 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '50.00', 'discountPercentage' => '33.33', 'discount_type' => NULL, 'tax_amount' => '15.00', 'dueAmount' => '0.00', 'paidAmount' => '145.00', 'totalAmount' => '145.00', 'invoiceNumber' => '#8', 'sales_type' => 'pick_up', 'saleDate' => '2025-12-06 09:07:12', 'status' => 'completed', 'order_status' => 'pending', 'sale_data' => NULL, 'meta' => '{"delivery_charge":"30","payment_method":"fullPayment"}', 'created_at' => '2025-12-06 09:07:12', 'updated_at' => '2025-12-06 09:07:12'),
            array('business_id' => '1', 'party_id' => NULL, 'address_id' => NULL, 'user_id' => '7', 'tax_id' => '2', 'kot_id' => NULL, 'table_id' => NULL, 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => NULL, 'billing_address_id' => '2', 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '50.00', 'discountPercentage' => '4.83', 'discount_type' => NULL, 'tax_amount' => '103.50', 'dueAmount' => '0.00', 'paidAmount' => '1118.50', 'totalAmount' => '1118.50', 'invoiceNumber' => '#9', 'sales_type' => 'pick_up', 'saleDate' => '2025-12-06 09:35:24', 'status' => 'completed', 'order_status' => 'pending', 'sale_data' => NULL, 'meta' => '{"delivery_charge":"30","payment_method":"fullPayment"}', 'created_at' => '2025-12-06 09:35:24', 'updated_at' => '2025-12-06 09:35:24')
        );


        Sale::insert($sales);

        $sale_details = array(
            array('sale_id' => '1', 'product_id' => '6', 'variation_id' => NULL, 'price' => '125.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '1', 'product_id' => '1', 'variation_id' => '2', 'price' => '370.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '2', 'product_id' => '4', 'variation_id' => NULL, 'price' => '370.00', 'quantities' => '5', 'instructions' => NULL),
            array('sale_id' => '3', 'product_id' => '3', 'variation_id' => '4', 'price' => '480.00', 'quantities' => '2', 'instructions' => NULL),
            array('sale_id' => '3', 'product_id' => '5', 'variation_id' => '7', 'price' => '189.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '4', 'product_id' => '2', 'variation_id' => '6', 'price' => '550.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '5', 'product_id' => '6', 'variation_id' => NULL, 'price' => '125.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '5', 'product_id' => '4', 'variation_id' => NULL, 'price' => '370.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '5', 'product_id' => '5', 'variation_id' => '7', 'price' => '185.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '6', 'product_id' => '2', 'variation_id' => '6', 'price' => '550.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '6', 'product_id' => '3', 'variation_id' => '3', 'price' => '270.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '7', 'product_id' => '2', 'variation_id' => '6', 'price' => '520.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '7', 'product_id' => '5', 'variation_id' => '7', 'price' => '205.00', 'quantities' => '2', 'instructions' => NULL),
            array('sale_id' => '8', 'product_id' => '12', 'variation_id' => NULL, 'price' => '150.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '9', 'product_id' => '11', 'variation_id' => NULL, 'price' => '910.00', 'quantities' => '1', 'instructions' => NULL),
            array('sale_id' => '9', 'product_id' => '6', 'variation_id' => NULL, 'price' => '125.00', 'quantities' => '1', 'instructions' => NULL)
        );


        SaleDetails::insert($sale_details);

        $sale_detail_options = array(
            array('sale_detail_id' => '2', 'option_id' => '3', 'modifier_id' => '1', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '4', 'option_id' => '1', 'modifier_id' => '3', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '5', 'option_id' => '4', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '5', 'option_id' => '6', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '6', 'option_id' => '1', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '6', 'option_id' => '2', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '6', 'option_id' => '3', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '9', 'option_id' => '5', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '10', 'option_id' => '1', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '10', 'option_id' => '2', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '10', 'option_id' => '3', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '11', 'option_id' => '2', 'modifier_id' => '3', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '12', 'option_id' => '1', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '12', 'option_id' => '2', 'modifier_id' => '2', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '13', 'option_id' => '4', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '13', 'option_id' => '5', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('sale_detail_id' => '13', 'option_id' => '6', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL)
        );

        SaleDetailOption::insert($sale_detail_options);
    }
}
