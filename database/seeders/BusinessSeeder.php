<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = array(
            array('plan_subscribe_id' => '1', 'business_category_id' => '1', 'companyName' => 'Acnoo Restaurant', 'slug' => 'acnoo-restaurant', 'will_expire' => now()->addYears(10), 'address' => 'Dhaka Bangladesh', 'phoneNumber' => '01564654562', 'pictureUrl' => NULL, 'subscriptionDate' => '2025-03-08 12:48:30', 'remainingShopBalance' => '47634.50', 'shopOpeningBalance' => '50000.00', 'vat_name' => 'GST11', 'vat_no' => '213', 'created_at' => '2025-03-08 12:48:30', 'updated_at' => '2025-03-08 12:52:18'),
        );

        Business::insert($businesses);
    }
}
