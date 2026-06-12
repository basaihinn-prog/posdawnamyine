<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessCategory;

class BusinessCategorySeeder extends Seeder
{
    public function run(): void
    {
        $business_categories = array(
            array('name' => 'Restaurant', 'description' => 'Delicious Chinese dishes with authentic flavors.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Chinese Food', 'description' => 'Delicious Chinese dishes with authentic flavors.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Desert Item', 'description' => 'Sweet desserts including cakes, pastries, and ice cream.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Burger', 'description' => 'Juicy burgers served with fries and sides.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Pizza', 'description' => 'Freshly baked pizzas with a variety of toppings.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Fast Food', 'description' => 'Quick meals like fries, sandwiches, and snacks.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Five Star', 'description' => 'Premium fine dining experience with international cuisine.', 'status' => 1, 'created_at' => now(), 'updated_at' => now()),
        );

        BusinessCategory::insert($business_categories);
    }
}
