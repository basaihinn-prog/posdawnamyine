<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Party;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\SaleDetails;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PurchaseDetails;
use Illuminate\Database\Seeder;
use App\Models\SaleDetailOption;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $current_date = now();

        $suppliers = array(
            array('name' => 'Theresa Mill', 'business_id' => '1', 'email' => 'theresa@gmail.com', 'type' => 'supplier', 'phone' => '01378493532', 'due' => '28190.00', 'opening_balance' => '27000.00', 'address' => 'Shamoli, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473820-546.png', 'status' => '1', 'notes' => 'I am from shamoli', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('name' => 'Jerome Bell', 'business_id' => '1', 'email' => 'terome@gmail.com', 'type' => 'supplier', 'phone' => '01887239856', 'due' => '16117.00', 'opening_balance' => '15000.00', 'address' => 'Dhanmundi, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473707-403.svg', 'status' => '1', 'notes' => 'I am from dhanmundi 32', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('name' => 'Jenny Wilson', 'business_id' => '1', 'email' => 'jenny@gmail.com', 'type' => 'supplier', 'phone' => '01967342956', 'due' => '17400.00', 'opening_balance' => '10000.00', 'address' => 'Shahbag, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473636-661.svg', 'status' => '1', 'notes' => 'I am from shahbag', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $customers = array(
            array('name' => 'Kathryn Murphy', 'business_id' => '1', 'email' => 'kathryn@gmail.com', 'type' => 'customer', 'phone' => '01598364712', 'due' => '37603.90', 'opening_balance' => '0.00', 'address' => 'Farmgate, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473296-652.svg', 'status' => '1', 'notes' => 'I am from farmgate', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('name' => 'Devon Lane', 'business_id' => '1', 'email' => 'devon@gmail.com', 'type' => 'customer', 'phone' => '01877593924', 'due' => '24105.00', 'opening_balance' => '0.00', 'address' => 'Tejgao, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757472983-833.svg', 'status' => '1', 'notes' => 'I am a regular customer', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('name' => 'Floyd Miles', 'business_id' => '1', 'email' => 'floyd@gmail.com', 'type' => 'customer', 'phone' => '01787942744', 'due' => '13334.50', 'opening_balance' => '0.00', 'address' => 'Mirpur 10, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473543-236.svg', 'status' => '1', 'notes' => 'I am from mirpur', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $sales = array(
            array('business_id' => '1', 'user_id' => '4', 'tax_id' => '2', 'staff_id' => '2', 'coupon_id' => NULL, 'payment_type_id' => '1', 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '20.00', 'discountPercentage' => '4.04', 'discount_type' => NULL, 'tax_amount' => '49.50', 'dueAmount' => '334.50', 'paidAmount' => '200.00', 'totalAmount' => '534.50', 'invoiceNumber' => '#1', 'sales_type' => 'dine_in', 'saleDate' => '2025-09-11 09:18:33', 'status' => 'completed', 'sale_data' => NULL, 'meta' => '{"tip":"10","delivery_charge":0,"payment_method":"duePayment"}', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'tax_id' => '2', 'staff_id' => NULL, 'coupon_id' => '3', 'payment_type_id' => '3', 'coupon_amount' => '1202.5', 'coupon_percentage' => '65', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'discount_type' => NULL, 'tax_amount' => '185.00', 'dueAmount' => '0.00', 'paidAmount' => '902.50', 'totalAmount' => '902.50', 'invoiceNumber' => '#2', 'sales_type' => 'pick_up', 'saleDate' => '2025-09-11 09:20:17', 'status' => 'completed', 'sale_data' => NULL, 'meta' => '{"tip":"70","delivery_charge":0,"payment_method":"fullPayment"}', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'tax_id' => '2', 'staff_id' => NULL, 'coupon_id' => NULL, 'payment_type_id' => NuLL, 'coupon_amount' => '0', 'coupon_percentage' => '0', 'discountAmount' => '50.00', 'discountPercentage' => '4.35', 'discount_type' => NULL, 'tax_amount' => '114.90', 'dueAmount' => '603.90', 'paidAmount' => '0.00', 'totalAmount' => '1353.90', 'invoiceNumber' => '#3', 'sales_type' => 'delivery', 'saleDate' => '2025-09-11 09:22:11', 'status' => 'completed', 'sale_data' => NULL, 'meta' => '{"tip":"20","delivery_charge":"120","payment_method":"duePayment"}', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $sale_details = array(
            array('product_id' => '6', 'variation_id' => NULL, 'price' => '125.00', 'quantities' => '1', 'instructions' => NULL),
            array('product_id' => '1', 'variation_id' => '2', 'price' => '370.00', 'quantities' => '1', 'instructions' => NULL),
            array('product_id' => '4', 'variation_id' => NULL, 'price' => '370.00', 'quantities' => '5', 'instructions' => NULL),
        );

        $sale_detail_options = array(
            array('option_id' => '3', 'modifier_id' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('option_id' => '1', 'modifier_id' => '3', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('option_id' => '4', 'modifier_id' => '4', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $purchases = array(
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '1', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'tax_amount' => '0.00', 'tax_percentage' => '0.00', 'dueAmount' => '7400.00', 'paidAmount' => '7000.00', 'totalAmount' => '14400.00', 'invoiceNumber' => '3', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '2', 'discountAmount' => '0.00', 'discountPercentage' => '0.00', 'tax_amount' => '0.00', 'tax_percentage' => '0.00', 'dueAmount' => '0.00', 'paidAmount' => '1185.00', 'totalAmount' => '1185.00', 'invoiceNumber' => '4', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => NuLL, 'discountAmount' => '150.00', 'discountPercentage' => '14.71', 'tax_amount' => '70.00', 'tax_percentage' => '6.86', 'dueAmount' => '940.00', 'paidAmount' => '0.00', 'totalAmount' => '940.00', 'invoiceNumber' => '3', 'purchaseDate' => '2025-09-10 00:00:00', 'purchase_data' => NULL, 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $purchase_details = array(
            array('ingredient_id' => '1', 'unit_id' => '1', 'unit_price' => '500', 'quantities' => '20'),
            array('ingredient_id' => '3', 'unit_id' => '1', 'unit_price' => '60', 'quantities' => '40'),
            array('ingredient_id' => '5', 'unit_id' => '1', 'unit_price' => '200', 'quantities' => '10'),
        );

        foreach ($sales as $key => $sale) {

            $customer = Party::create($customers[$key]);
            $supplier = Party::create($suppliers[$key]);

            $sale_data = Sale::create($sale + [
                'party_id' => $customer->id
            ]);

            $sale_detail = SaleDetails::create($sale_details[$key] + [
                'sale_id' => $sale_data->id
            ]);

            SaleDetailOption::create($sale_detail_options[$key] + [
                'sale_detail_id' => $sale_detail->id
            ]);

            $purchase_data = Purchase::create($purchases[$key] + [
                'party_id' => $supplier->id
            ]);
            PurchaseDetails::create($purchase_details[$key] + [
                'purchase_id' => $purchase_data->id
            ]);
        }

        $expense_categories = array(
            array('categoryName' => 'Rent', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('categoryName' => 'Marketing', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('categoryName' => 'Event Catering', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $expenses = array(
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '1', 'amount' => '700.00', 'expanseFor' => 'Wifi and Electric Bill', 'referenceNo' => 'UT-6732', 'note' => 'I paid electric, water and internet bill', 'expenseDate' => '2025-09-09 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '2', 'amount' => '25000.00', 'expanseFor' => 'Monthly Office Rent', 'referenceNo' => 'RN-0923', 'note' => 'Rent paid for September', 'expenseDate' => '2025-09-01 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '3', 'amount' => '5000.00', 'expanseFor' => 'Facebook Ads', 'referenceNo' => 'MT-4551', 'note' => 'Social media marketing for new product', 'expenseDate' => '2025-09-05 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        foreach ($expense_categories as $key => $expense_category) {
            $expenses_category = ExpenseCategory::create($expense_category);
            Expense::create($expenses[$key] + [
                'expense_category_id' => $expenses_category->id
            ]);
        }

        $income_categories = array(
            array('categoryName' => 'Food Sales', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('categoryName' => 'Delivery', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('categoryName' => 'Catering', 'business_id' => '1', 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        $incomes = array(
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '4', 'amount' => '2300.00', 'incomeFor' => 'Offline Food Sales', 'referenceNo' => 'FS-8735', 'note' => 'Today\'s offline sale was very good', 'incomeDate' => '2025-09-09 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '1', 'amount' => '1200.00', 'incomeFor' => 'Home Delivery', 'referenceNo' => 'DL-7812', 'note' => 'Delivered 25 orders today', 'incomeDate' => '2025-09-09 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
            array('business_id' => '1', 'user_id' => '4', 'payment_type_id' => '2', 'amount' => '4500.00', 'incomeFor' => 'Catering Event', 'referenceNo' => 'CT-9941', 'note' => 'Catering for wedding event', 'incomeDate' => '2025-09-08 00:00:00', 'created_at' => $current_date, 'updated_at' => $current_date),
        );

        foreach ($income_categories as $key => $income_category) {
            $incomes_category = IncomeCategory::create($income_category);
            Income::create($incomes[$key] + [
                'income_category_id' => $incomes_category->id
            ]);
        }
    }
}
