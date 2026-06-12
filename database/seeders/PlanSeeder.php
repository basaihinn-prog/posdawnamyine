<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = array(
            array('subscriptionName' => 'Free','duration' => '7','offerPrice' => '0.00','subscriptionPrice' => '0.00','status' => '1','is_popular' => '0','features' => '[{"feature":"Free Lifetime Update","status":"1"},{"feature":"Premium Customer Support","status":"1"},{"feature":"Android & iOS App Support","status":"1"},{"feature":"Custom Invoice Branding","status":"1"},{"feature":"Easily Manage your Business","status":"0"},{"feature":"Unlimited Usage","status":"0"},{"feature":"Free Data Backup","status":"0"}]','icon' => 'uploads/25/08/1754302744-167.svg','created_at' => '2024-06-04 18:08:12','updated_at' => '2025-08-04 16:19:04'),
            array('subscriptionName' => 'Standard','duration' => '30','offerPrice' => '5.00','subscriptionPrice' => '10.00','status' => '1','is_popular' => '0','features' => '[{"feature":"Free Lifetime Update","status":"1"},{"feature":"Premium Customer Support","status":"1"},{"feature":"Android & iOS App Support","status":"1"},{"feature":"Custom Invoice Branding","status":"1"},{"feature":"Easily Manage your Business","status":"1"},{"feature":"Unlimited Usage","status":"0"},{"feature":"Free Data Backup","status":"0"}]','icon' => 'uploads/25/08/1754302783-482.svg','created_at' => '2024-06-04 18:08:12','updated_at' => '2025-08-04 16:19:43'),
            array('subscriptionName' => 'Premium','duration' => '180','offerPrice' => '50.00','subscriptionPrice' => '60.00','status' => '1','is_popular' => '0','features' => '[{"feature":"Free Lifetime Update","status":"1"},{"feature":"Premium Customer Support","status":"1"},{"feature":"Android & iOS App Support","status":"1"},{"feature":"Custom Invoice Branding","status":"1"},{"feature":"Easily Manage your Business","status":"1"},{"feature":"Unlimited Usage","status":"1"},{"feature":"Free Data Backup","status":"1"}]','icon' => 'uploads/25/08/1754302826-551.svg','created_at' => '2024-06-04 18:08:12','updated_at' => '2025-08-04 16:20:26')
          );

        Plan::insert($plans);
    }
}
