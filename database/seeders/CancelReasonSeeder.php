<?php

namespace Database\Seeders;

use App\Models\CancelReason;
use Illuminate\Database\Seeder;

class CancelReasonSeeder extends Seeder
{
    public function run(): void
    {
        $cancel_reasons = array(
            array('business_id' => '1', 'type' => 'kot', 'reason' => 'Ingredient not available', 'status' => '1', 'created_at' => now(), 'updated_at' => now()),
            array('business_id' => '1', 'type' => 'kot', 'reason' => 'Preparation  time too long', 'status' => '1', 'created_at' => now(), 'updated_at' => now()),
            array('business_id' => '1', 'type' => 'kot', 'reason' => 'Quality issue with ingredients', 'status' => '1', 'created_at' => now(), 'updated_at' => now()),
            array('business_id' => '1', 'type' => 'kot', 'reason' => 'Restaurant Closing Early', 'status' => '1', 'created_at' => now(), 'updated_at' => now()),
            array('business_id' => '1', 'type' => 'kot', 'reason' => 'Others', 'status' => '1', 'created_at' => now(), 'updated_at' => now()),
        );

        CancelReason::insert($cancel_reasons);
    }
}
