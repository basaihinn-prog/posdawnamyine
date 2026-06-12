<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = array(
            array('business_id' => '1', 'name' => 'Rakibul Islam', 'email' => 'rakibulislam@gmail.com', 'phone' => '01711223344', 'address' => 'Dhanmondi, Dhaka, Bangladesh', 'designation' => 'manager', 'image' => 'uploads/25/11/1763178683-594.png', 'created_at' => '2025-09-09 16:20:48', 'updated_at' => '2025-11-15 03:51:23'),
            array('business_id' => '1', 'name' => 'David Jones', 'email' => 'devidjones@gmail.com', 'phone' => '01327483559', 'address' => 'Motijhil, Dhaka, Bangladesh', 'designation' => 'chef', 'image' => 'uploads/25/11/1763178763-817.png', 'created_at' => '2025-11-15 03:52:43', 'updated_at' => '2025-11-15 03:52:43'),
            array('business_id' => '1', 'name' => 'Nusrat Jahan', 'email' => 'nusratjahan@gmail.com', 'phone' => '01822334455', 'address' => 'Gulshan, Dhaka, Bangladesh', 'designation' => 'waiter', 'image' => 'uploads/25/11/1763178655-283.png', 'created_at' => '2025-09-09 16:22:15', 'updated_at' => '2025-11-15 03:50:55'),
            array('business_id' => '1', 'name' => 'Manik Mia', 'email' => 'manikmia@gmail.com', 'phone' => '01798342856', 'address' => 'Modijhil, Dhaka, Bangladesh', 'designation' => 'chef', 'image' => 'uploads/25/11/1763178668-840.png', 'created_at' => '2025-09-09 16:22:15', 'updated_at' => '2025-11-15 03:51:08'),
            array('business_id' => '1', 'name' => 'Mehedi Hasan', 'email' => 'mehedihasan@gmail.com', 'phone' => '01933445566', 'address' => 'Chawkbazar, Chattogram, Bangladesh', 'designation' => 'chef', 'image' => 'uploads/25/11/1763178644-779.png', 'created_at' => '2025-09-09 16:23:30', 'updated_at' => '2025-11-15 03:50:44'),
            array('business_id' => '1', 'name' => 'Sharmin Akter', 'email' => 'sharminakter@gmail.com', 'phone' => '01644556677', 'address' => 'Kazla, Rajshahi, Bangladesh', 'designation' => 'cleaner', 'image' => 'uploads/25/11/1763178628-610.png', 'created_at' => '2025-09-09 16:24:45', 'updated_at' => '2025-11-15 03:50:28'),
            array('business_id' => '1', 'name' => 'Mim Rahman', 'email' => 'mimrahman@gmail.com', 'phone' => '01877889900', 'address' => 'Khulna City, Khulna, Bangladesh', 'designation' => 'chef', 'image' => 'uploads/25/11/1763178556-167.png', 'created_at' => '2025-09-09 16:28:30', 'updated_at' => '2025-11-15 03:49:17'),
            array('business_id' => '1', 'name' => 'Tanvir Ahmed', 'email' => 'tanvirahmed@gmail.com', 'phone' => '01555667788', 'address' => 'Agrabad, Chattogram, Bangladesh', 'designation' => 'driver', 'image' => 'uploads/25/11/1763178614-706.png', 'created_at' => '2025-09-09 16:26:00', 'updated_at' => '2025-11-15 03:50:14'),
            array('business_id' => '1', 'name' => 'Rafiq Chowdhury', 'email' => 'rafiqchowdhury@gmail.com', 'phone' => '01766778899', 'address' => 'Sylhet Sadar, Sylhet, Bangladesh', 'designation' => 'delivery_boy', 'image' => 'uploads/25/11/1763178603-681.jpg', 'created_at' => '2025-09-09 16:27:15', 'updated_at' => '2025-11-15 03:50:03'),
            array('business_id' => '1', 'name' => 'Hridhoy Hasan', 'email' => 'hridoyhasan@gamil.com', 'phone' => '01523985647', 'address' => 'Zatrabari, Dhaka, Bangladesh', 'designation' => 'chef', 'image' => 'uploads/25/11/1763178845-632.png', 'created_at' => '2025-11-15 03:54:05', 'updated_at' => '2025-11-15 03:54:05'),
            array('business_id' => '1','name' => 'Harry Mark','email' => 'kitchen@gmail.com','phone' => '0635333388','address' => 'Dhaka Bangladesh','designation' => 'kitchen','image' => NULL,'created_at' => '2026-02-02 11:43:54','updated_at' => '2026-02-02 11:44:15')
        );

        Staff::insert($staff);
    }
}
