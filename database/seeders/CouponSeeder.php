<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = array(
            array('name' => 'Git Code', 'business_id' => '1', 'image' => 'uploads/25/09/1757471541-560.png', 'code' => 'GC-3285', 'start_date' => '2026-01-10', 'end_date' => '2026-11-28', 'discount_type' => 'percentage', 'discount' => '50.00', 'description' => 'This is gift code', 'created_at' => '2025-07-19 16:33:03', 'updated_at' => '2025-09-10 08:32:21'),
            array('name' => 'Discount', 'business_id' => '1', 'image' => 'uploads/25/09/1757471280-802.jpg', 'code' => 'DT-3487', 'start_date' => '2025-04-09', 'end_date' => '2025-09-09', 'discount_type' => 'flat', 'discount' => '50.00', 'description' => 'Discount coupon', 'created_at' => '2025-07-19 16:33:40', 'updated_at' => '2025-09-10 08:28:00'),
            array('name' => 'Combo Offers', 'business_id' => '1', 'image' => 'uploads/25/09/1757471011-13.svg', 'code' => 'CO-4567', 'start_date' => '2025-09-10', 'end_date' => '2027-09-27', 'discount_type' => 'percentage', 'discount' => '65.00', 'description' => 'Greatest offer ever', 'created_at' => '2025-09-10 08:23:33', 'updated_at' => '2025-09-10 08:23:33')
        );

        Coupon::insert($coupons);
    }
}
