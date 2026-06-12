<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time_slots = array(
            array('business_id' => '1','day' => 'monday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:00:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 08:58:41','updated_at' => '2025-12-08 08:58:41'),
            array('business_id' => '1','day' => 'monday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 08:58:41','updated_at' => '2025-12-08 08:58:41'),
            array('business_id' => '1','day' => 'monday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '22:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 08:58:41','updated_at' => '2025-12-08 08:58:41'),
            array('business_id' => '1','day' => 'tuesday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'tuesday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'tuesday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'thursday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'thursday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'thursday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'wednesday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'wednesday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'wednesday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'friday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'friday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'friday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'saturday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'saturday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'saturday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'sunday','slot_type' => 'breakfast','start_time' => '08:00:00','end_time' => '11:59:00','time_difference' => '30','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'sunday','slot_type' => 'lunch','start_time' => '12:00:00','end_time' => '17:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30'),
            array('business_id' => '1','day' => 'sunday','slot_type' => 'dinner','start_time' => '18:00:00','end_time' => '21:00:00','time_difference' => '60','is_available' => '1','created_at' => '2025-12-08 12:09:30','updated_at' => '2025-12-08 12:09:30')
        );

        TimeSlot::insert($time_slots);
    }
}
