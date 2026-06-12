<?php

namespace Database\Seeders;

use App\Models\Modifier;
use Illuminate\Database\Seeder;

class ModifierSeeder extends Seeder
{
    public function run(): void
    {
        $modifiers = array(
            array('business_id' => '1', 'product_id' => '1', 'modifier_group_id' => '1', 'is_required' => '1', 'is_multiple' => '0', 'created_at' => '2025-09-10 14:00:19', 'updated_at' => '2025-09-10 15:09:44'),
            array('business_id' => '1', 'product_id' => '2', 'modifier_group_id' => '1', 'is_required' => '0', 'is_multiple' => '1', 'created_at' => '2025-09-10 14:00:19', 'updated_at' => '2025-09-10 14:04:24'),
            array('business_id' => '1', 'product_id' => '3', 'modifier_group_id' => '1', 'is_required' => '1', 'is_multiple' => '0', 'created_at' => '2025-09-10 14:00:19', 'updated_at' => '2025-09-10 14:04:53'),
            array('business_id' => '1', 'product_id' => '5', 'modifier_group_id' => '2', 'is_required' => '0', 'is_multiple' => '1', 'created_at' => '2025-09-10 15:13:01', 'updated_at' => '2025-09-10 15:13:18')
        );

        Modifier::insert($modifiers);
    }
}
