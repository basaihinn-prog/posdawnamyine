<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = array(
            array('business_id' => '1','name' => 'Meat','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Egg','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Rice','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Atta','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Chicken','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Potato','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Onion','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Salt','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Sugar','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Cheese','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Milk','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Lemon','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
            array('business_id' => '1','name' => 'Tea','created_at' => '2025-07-12 11:51:15','updated_at' => '2025-07-12 11:51:15'),
          );

          Ingredient::insert($ingredients);
    }
}
