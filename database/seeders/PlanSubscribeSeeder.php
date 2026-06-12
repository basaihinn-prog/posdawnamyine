<?php

namespace Database\Seeders;

use App\Models\PlanSubscribe;
use Illuminate\Database\Seeder;

class PlanSubscribeSeeder extends Seeder
{
    public function run(): void
    {
        $plan_subscribes = array(
            array('plan_id' => '1','business_id' => '1','gateway_id' => 10,'price' => '0.00','payment_status' => 'unpaid','duration' => '7','notes' => NULL,'created_at' => now(),'updated_at' => now()),
        );

        PlanSubscribe::insert($plan_subscribes);
    }
}
