<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = array(
            array('name' => 'VAT','vat_on_sale' => '0','business_id' => '1','rate' => '15','sub_tax' => NULL,'status' => '1','created_at' => '2025-03-15 12:19:19','updated_at' => '2025-03-15 12:19:19'),
            array('name' => 'GST','vat_on_sale' => '1','business_id' => '1','rate' => '10','sub_tax' => NULL,'status' => '1','created_at' => '2025-03-15 12:20:43','updated_at' => '2025-03-15 12:20:43'),
            array('name' => 'CGST','vat_on_sale' => '0','business_id' => '1','rate' => '5','sub_tax' => NULL,'status' => '1','created_at' => '2025-03-15 12:20:43','updated_at' => '2025-03-15 12:20:43'),
            array('name' => 'VAT + GST','vat_on_sale' => '0','business_id' => '1','rate' => '20','sub_tax' => '[{"id":1,"name":"VAT","rate":15},{"id":2,"name":"GST","rate":10}]','status' => '1','created_at' => '2025-03-15 12:21:02','updated_at' => '2025-03-15 12:21:02'),
            array('name' => 'Standard VAT','vat_on_sale' => '0','business_id' => '1','rate' => '12','sub_tax' => '[{"id":3,"name":"CGST","rate":5}]','status' => '1','created_at' => '2025-03-15 12:21:02','updated_at' => '2025-03-15 12:21:02')
        );

        Tax::insert($taxes);
    }
}
