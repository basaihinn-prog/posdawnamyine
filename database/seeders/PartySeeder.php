<?php

namespace Database\Seeders;

use App\Models\DeliveryAddress;
use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    public function run(): void
    {
        $parties = array(
            array('name' => 'Theresa Mill', 'business_id' => '1', 'email' => 'theresa@gmail.com', 'type' => 'supplier', 'phone' => '01378493532', 'due' => '28190.00', 'opening_balance' => '27000.00', 'address' => 'Shamoli, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473820-546.png', 'status' => '1', 'notes' => 'I am from shamoli', 'created_at' => '2025-03-24 10:07:22', 'updated_at' => '2025-09-10 16:53:31'),
            array('name' => 'Kathryn Murphy', 'business_id' => '1', 'email' => 'kathryn@gmail.com', 'type' => 'customer', 'phone' => '01598364712', 'due' => '37603.90', 'opening_balance' => '0.00', 'address' => 'Farmgate, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473296-652.svg', 'status' => '1', 'notes' => 'I am from farmgate', 'created_at' => '2025-03-24 10:08:38', 'updated_at' => '2025-09-11 10:18:05'),
            array('name' => 'Jerome Bell', 'business_id' => '1', 'email' => 'terome@gmail.com', 'type' => 'supplier', 'phone' => '01887239856', 'due' => '16117.00', 'opening_balance' => '15000.00', 'address' => 'Dhanmundi, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473707-403.svg', 'status' => '1', 'notes' => 'I am from dhanmundi 32', 'created_at' => '2025-03-24 10:09:31', 'updated_at' => '2025-09-10 17:03:41'),
            array('name' => 'Devon Lane', 'business_id' => '1', 'email' => 'devon@gmail.com', 'type' => 'customer', 'phone' => '01877593924', 'due' => '24105.00', 'opening_balance' => '0.00', 'address' => 'Tejgao, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757472983-833.svg', 'status' => '1', 'notes' => 'I am a regular customer', 'created_at' => '2025-03-24 10:10:15', 'updated_at' => '2025-09-11 10:17:44'),
            array('name' => 'Floyd Miles', 'business_id' => '1', 'email' => 'floyd@gmail.com', 'type' => 'customer', 'phone' => '01787942744', 'due' => '13334.50', 'opening_balance' => '0.00', 'address' => 'Mirpur 10, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473543-236.svg', 'status' => '1', 'notes' => 'I am from mirpur', 'created_at' => '2025-09-10 09:05:43', 'updated_at' => '2025-09-11 09:18:33'),
            array('name' => 'Jenny Wilson', 'business_id' => '1', 'email' => 'jenny@gmail.com', 'type' => 'supplier', 'phone' => '01967342956', 'due' => '17400.00', 'opening_balance' => '10000.00', 'address' => 'Shahbag, Dhaka, Bangladesh', 'image' => 'uploads/25/09/1757473636-661.svg', 'status' => '1', 'notes' => 'I am from shahbag', 'created_at' => '2025-09-10 09:07:16', 'updated_at' => '2025-09-11 10:17:19')
        );

        Party::insert($parties);

        $delivery_addresses = array(
            array('party_id' => '4', 'name' => 'Devon Lane', 'phone' => '01877593924', 'address' => 'Tejgao, Dhaka, Bangladesh', 'created_at' => '2025-09-10 08:56:23', 'updated_at' => '2025-09-10 08:56:23'),
            array('party_id' => '2', 'name' => 'Kathryn Murphy', 'phone' => '01598364712', 'address' => 'Farmgate, Dhaka, Bangladesh', 'created_at' => '2025-09-10 09:01:36', 'updated_at' => '2025-09-10 09:01:36'),
            array('party_id' => '5', 'name' => 'Floyd Miles', 'phone' => '01787942744', 'address' => 'Mirpur 10, Dhaka, Bangladesh', 'created_at' => '2025-09-10 09:05:43', 'updated_at' => '2025-09-10 09:05:43')
        );

        DeliveryAddress::insert($delivery_addresses);
    }
}
