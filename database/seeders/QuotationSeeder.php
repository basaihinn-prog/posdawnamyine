<?php

namespace Database\Seeders;

use App\Models\Quotation;
use App\Models\QuotationDetailOption;
use App\Models\QuotationDetails;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $quotations = array(
            array('business_id' => '1', 'party_id' => '5', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'coupon_id' => '3', 'payment_type_id' => '1', 'coupon_amount' => '861.9', 'coupon_percentage' => '65', 'discountAmount' => '20', 'discountPercentage' => '1.51', 'discount_type' => NULL, 'tax_amount' => '132.6', 'totalAmount' => '586.7', 'dueAmount' => '386.7', 'paidAmount' => '200', 'invoiceNumber' => '#1', 'quotationDate' => '2025-09-11 08:39:11', 'meta' => '{"tip":"10"}', 'created_at' => '2025-09-11 08:39:11', 'updated_at' => '2025-09-11 08:39:11'),
            array('business_id' => '1', 'party_id' => '4', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'coupon_id' => NULL, 'payment_type_id' => '2', 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '15', 'discountPercentage' => '0.53', 'discount_type' => NULL, 'tax_amount' => '281', 'totalAmount' => '3086', 'dueAmount' => '3086', 'paidAmount' => '0', 'invoiceNumber' => '#2', 'quotationDate' => '2025-09-11 08:41:22', 'meta' => '{"tip":"10"}', 'created_at' => '2025-09-11 08:41:22', 'updated_at' => '2025-09-11 09:05:12'),
            array('business_id' => '1', 'party_id' => '2', 'address_id' => NULL, 'user_id' => '4', 'tax_id' => '2', 'coupon_id' => NULL, 'payment_type_id' => '2', 'coupon_amount' => '1330.55', 'coupon_percentage' => '65', 'discountAmount' => '300', 'discountPercentage' => '14.66', 'discount_type' => NULL, 'tax_amount' => '204.7', 'totalAmount' => '636.15', 'dueAmount' => '0.00', 'paidAmount' => '636.15', 'invoiceNumber' => '#3', 'quotationDate' => '2025-09-11 08:44:20', 'meta' => '{"tip":"15"}', 'created_at' => '2025-09-11 08:44:20', 'updated_at' => '2025-09-11 08:45:21')
        );

        Quotation::insert($quotations);

        $quotation_details = array(
            array('quotation_id' => '1', 'product_id' => '5', 'variation_id' => '8', 'price' => '269.00', 'quantities' => '4', 'instructions' => NULL, 'created_at' => '2025-09-11 08:39:11', 'updated_at' => '2025-09-11 08:39:11'),
            array('quotation_id' => '1', 'product_id' => '6', 'variation_id' => NULL, 'price' => '125.00', 'quantities' => '2', 'instructions' => NULL, 'created_at' => '2025-09-11 08:39:11', 'updated_at' => '2025-09-11 08:39:11'),
            array('quotation_id' => '3', 'product_id' => '2', 'variation_id' => '6', 'price' => '550.00', 'quantities' => '1', 'instructions' => NULL, 'created_at' => '2025-09-11 08:45:21', 'updated_at' => '2025-09-11 08:45:21'),
            array('quotation_id' => '3', 'product_id' => '1', 'variation_id' => '2', 'price' => '360.00', 'quantities' => '2', 'instructions' => NULL, 'created_at' => '2025-09-11 08:45:21', 'updated_at' => '2025-09-11 08:45:21'),
            array('quotation_id' => '3', 'product_id' => '5', 'variation_id' => '8', 'price' => '259.00', 'quantities' => '3', 'instructions' => NULL, 'created_at' => '2025-09-11 08:45:21', 'updated_at' => '2025-09-11 08:45:21'),
            array('quotation_id' => '2', 'product_id' => '4', 'variation_id' => NULL, 'price' => '370.00', 'quantities' => '5', 'instructions' => NULL, 'created_at' => '2025-09-11 09:05:12', 'updated_at' => '2025-09-11 09:05:12'),
            array('quotation_id' => '2', 'product_id' => '3', 'variation_id' => '4', 'price' => '480.00', 'quantities' => '2', 'instructions' => NULL, 'created_at' => '2025-09-11 09:05:12', 'updated_at' => '2025-09-11 09:05:12')
        );

        QuotationDetails::insert($quotation_details);

        $quotation_detail_options = array(
            array('quotation_detail_id' => '1', 'option_id' => '4', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('quotation_detail_id' => '1', 'option_id' => '6', 'modifier_id' => '4', 'created_at' => NULL, 'updated_at' => NULL),
            array('quotation_detail_id' => '7', 'option_id' => '1', 'modifier_id' => '3', 'created_at' => NULL, 'updated_at' => NULL)
        );
        QuotationDetailOption::insert($quotation_detail_options);
    }
}
