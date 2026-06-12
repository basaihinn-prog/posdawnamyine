<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = array(
            array('business_id' => NULL, 'star' => '5', 'client_name' => 'Carol Cash', 'client_image' => 'uploads/25/06/1750825205-820.jpeg', 'work_at' => 'Acnoo', 'text' => 'Great work! Delivered everything on time and exactly as expected.', 'created_at' => '2025-06-24 15:25:25', 'updated_at' => '2025-06-24 15:25:25'),
            array('business_id' => NULL, 'star' => '4', 'client_name' => 'Stacey Duran', 'client_image' => 'uploads/25/06/1750825268-323.jpeg', 'work_at' => 'Fiverr', 'text' => 'Very professional and communicates clearly. Highly recommended!', 'created_at' => '2025-06-24 15:25:25', 'updated_at' => '2025-06-24 15:25:25'),
            array('business_id' => NULL, 'star' => '3', 'client_name' => 'Daniel Martin', 'client_image' => 'uploads/25/06/1750825278-278.jpeg', 'work_at' => 'Toptal', 'text' => 'Top-notch developer with strong problem-solving skills.', 'created_at' => '2025-06-24 15:25:25', 'updated_at' => '2025-06-24 15:25:25'),
            array('business_id' => NULL, 'star' => '5', 'client_name' => 'Emily Davis', 'client_image' => 'uploads/25/06/1750825291-653.jpeg', 'work_at' => 'Youtube', 'text' => 'The project was completed faster than expected. Loved the outcome!', 'created_at' => '2025-06-24 15:25:25', 'updated_at' => '2025-06-24 15:25:25'),
        );

        Testimonial::insert($testimonials);
    }
}
