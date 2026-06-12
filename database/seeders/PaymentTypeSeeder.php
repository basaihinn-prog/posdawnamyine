<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $payment_types = array(
            array('business_id' => '1', 'name' => 'Cash', 'is_view' => '1', 'status' => '1', 'created_at' => '2025-04-16 16:50:08', 'updated_at' => '2025-04-16 16:50:08'),
            array('business_id' => '1', 'name' => 'Card', 'is_view' => '1', 'status' => '1', 'created_at' => '2025-04-16 16:50:19', 'updated_at' => '2025-04-16 16:50:19'),
            array('business_id' => '1', 'name' => 'Bank', 'is_view' => '0', 'status' => '1', 'created_at' => '2025-04-16 16:50:19', 'updated_at' => '2025-04-16 16:50:19'),
            array('business_id' => '1', 'name' => 'Bkash', 'is_view' => '0', 'status' => '1', 'created_at' => '2025-04-16 16:50:19', 'updated_at' => '2025-04-16 16:50:19'),
            array('business_id' => '1', 'name' => 'Nagad', 'is_view' => '0', 'status' => '1', 'created_at' => '2025-04-16 16:50:19', 'updated_at' => '2025-04-16 16:50:19'),
            array('business_id' => '1', 'name' => 'COD', 'is_view' => '0', 'status' => '1', 'created_at' => '2025-04-16 16:50:19', 'updated_at' => '2025-04-16 16:50:19'),
        );

        PaymentType::insert($payment_types);
    }
}
