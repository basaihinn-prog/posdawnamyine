<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = array(
            array('id' => '1', 'question' => 'Is there a free trial available?', 'answer' => 'Yes, you can try us for free for 30 days. If you want, we’ll provide you with a free, personalized 30-minute onboarding call to get you up and running as soon as possible.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
            array('id' => '2', 'question' => 'Can I change my plan later?', 'answer' => 'Yes, you can upgrade or downgrade anytime from your account settings.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
            array('id' => '3', 'question' => 'What is your cancellation policy?', 'answer' => 'You can cancel anytime. Your subscription will remain active until the end of the billing period.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
            array('id' => '4', 'question' => 'Can other info be added to an invoice?', 'answer' => 'Yes, you can add custom billing details like your company name, address, and VAT number.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
            array('id' => '5', 'question' => 'How does billing work?', 'answer' => 'Billing is done on a monthly or yearly basis depending on your subscription plan.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
            array('id' => '6', 'question' => 'How do I change my account email?', 'answer' => 'You can change your email address from your profile settings at any time.', 'status' => '1', 'created_at' => '2025-06-30 13:02:03', 'updated_at' => '2025-06-30 13:02:03'),
        );

        Faq::insert($faqs);
    }
}
