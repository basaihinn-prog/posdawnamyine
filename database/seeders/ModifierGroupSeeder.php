<?php

namespace Database\Seeders;

use App\Models\ModifierGroupOptions;
use App\Models\ModifierGroups;
use Illuminate\Database\Seeder;

class ModifierGroupSeeder extends Seeder
{
    public function run(): void
    {
        $modifier_groups = array(
            array('business_id' => '1', 'name' => 'Extra Flavor', 'description' => 'Optional flavor boosts for a tastier experience.', 'created_at' => '2025-09-10 14:00:19', 'updated_at' => '2025-09-10 14:00:19'),
            array('business_id' => '1', 'name' => 'Beverage Extras', 'description' => 'Perfect extras to pair with your favorite beverages.', 'created_at' => '2025-09-10 14:02:11', 'updated_at' => '2025-09-10 14:02:11')
        );

        ModifierGroups::insert($modifier_groups);

        $modifier_group_options = array(
            array('modifier_group_id' => '1', 'name' => 'Extra Cheese', 'price' => '50.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:11:09', 'updated_at' => '2025-09-10 15:11:09'),
            array('modifier_group_id' => '1', 'name' => 'Extra Spice', 'price' => '20.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:11:09', 'updated_at' => '2025-09-10 15:11:09'),
            array('modifier_group_id' => '1', 'name' => 'Extra Sauce', 'price' => '30.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:11:09', 'updated_at' => '2025-09-10 15:11:09'),
            array('modifier_group_id' => '2', 'name' => 'Extra Ice', 'price' => '10.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:14:06', 'updated_at' => '2025-09-10 15:14:06'),
            array('modifier_group_id' => '2', 'name' => 'Extra Lemon', 'price' => '16.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:14:06', 'updated_at' => '2025-09-10 15:14:06'),
            array('modifier_group_id' => '2', 'name' => 'Extra Sugar', 'price' => '10.00', 'is_available' => '1', 'created_at' => '2025-09-10 15:14:06', 'updated_at' => '2025-09-10 15:14:06')
        );

        ModifierGroupOptions::insert($modifier_group_options);
    }
}
