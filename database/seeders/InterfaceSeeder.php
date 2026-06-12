<?php

namespace Database\Seeders;

use App\Models\PosAppInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterfaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pos_app_interfaces = array(
            array('image' => 'uploads/25/08/1754306220-873.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:00'),
            array('image' => 'uploads/25/08/1754306232-449.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:12'),
            array('image' => 'uploads/25/08/1754306243-80.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:23'),
            array('image' => 'uploads/25/08/1754306264-725.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:44'),
            array('image' => 'uploads/25/08/1754306253-985.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:33'),
            array('image' => 'uploads/25/08/1754306278-254.svg','status' => '1','created_at' => '2025-08-04 17:12:48','updated_at' => '2025-08-04 17:17:58')
          );

        PosAppInterface::insert($pos_app_interfaces);
    }
}
