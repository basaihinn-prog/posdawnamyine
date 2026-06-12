<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = array(
            array('id'=> '1','title'=> 'Quick Sales','bg_color'=> '#ECEFFF','image'=> 'uploads/25/06/1750740745-712.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '2','title'=> 'Parties','bg_color'=> '#FFF0F4','image'=> 'uploads/25/06/1751089627-398.svg','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '3','title'=> 'Sales List','bg_color'=> '#E8F7EF','image'=> 'uploads/25/06/1750741097-126.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '4','title'=> 'Estimate List','bg_color'=> '#FFF1E6','image'=> 'uploads/25/06/1750741147-877.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '5','title'=> 'Purchase List','bg_color'=> '#F7ECFF','image'=> 'uploads/25/06/1750741184-781.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '6','title'=> 'Due List','bg_color'=> '#E1FFFD','image'=> 'uploads/25/06/1750741221-1.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '7','title'=> 'Item List','bg_color'=> '#ECEFFF','image'=> 'uploads/25/06/1750741256-984.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '8','title'=> 'Table','bg_color'=> '#E4FFEB','image'=> 'uploads/25/06/1750741279-288.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '9','title'=> 'Loss/Profit','bg_color'=> '#FBECE0','image'=> 'uploads/25/06/1750741311-645.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '10','title'=> 'Stocks','bg_color'=> '#F3E6FF','image'=> 'uploads/25/06/1751089637-251.svg','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '11','title'=> 'Income','bg_color'=> '#DFF4FE','image'=> 'uploads/25/06/1750741368-24.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '12','title'=> 'Expense','bg_color'=> '#FFE7E2','image'=> 'uploads/25/06/1750741402-486.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '13','title'=> 'VAT/Tax','bg_color'=> '#DFF4FE','image'=> 'uploads/25/06/1750741433-85.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '14','title'=> '100+ Languages','bg_color'=> '#DFF4FE','image'=> 'uploads/25/06/1750741499-341.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '15','title'=> 'Multi Currency','bg_color'=> '#FFF2E0','image'=> 'uploads/25/06/1750741564-484.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
            array('id'=> '16','title'=> 'More Features..','bg_color'=> '#F0E8FF','image'=> 'uploads/25/06/1750741652-846.png','status'=> '1','created_at'=> '2025-06-24 10:52:25','updated_at'=> '2025-06-24 10:52:25'),
        );

        Feature::insert($features);
    }
}
