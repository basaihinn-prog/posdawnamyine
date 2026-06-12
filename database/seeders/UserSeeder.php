<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = array(
            array('business_id' => '1','staff_id' => NULL,'email' => 'restaurant@acnoo.com','name' => 'Acnoo Restaurant','role' => 'shop-owner','phone' => '056465456','gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => NULL,'fcm_token' => 'cBggS44YC2dXLv3vExCiGT:APA91bFU4SREyiAg3D9GMdudj4e16kaLRckbC-SHQy43U5INdZxDRaOc7P0KKsSpunYjsLghPOVgm0FSF-HWuhMctIgWl_JzY4u1ISqUrE4k13iZLsHpfjo','password' => '$2y$10$XGIM0YQxGoOz454IbphYmuUmsZdPpu.aUjAj717UrcMdsjpld9BOK','status' => NULL,'email_verified_at' => '2026-01-29 14:49:46','remember_token' => NULL,'created_at' => '2026-01-29 14:49:46','updated_at' => '2026-01-29 14:50:43'),
            array('business_id' => '1','staff_id' => '4','email' => 'chef@acnoo.com','name' => 'Chef','role' => 'chef','phone' => '01933445566','gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => '{"notification":{"view":"1"},"sales":{"view":"1"},"salesReport":{"view":"1"}}','fcm_token' => 'cBggS44YC2dXLv3vExCiGT:APA91bFU4SREyiAg3D9GMdudj4e16kaLRckbC-SHQy43U5INdZxDRaOc7P0KKsSpunYjsLghPOVgm0FSF-HWuhMctIgWl_JzY4u1ISqUrE4k13iZLsHpfjo','password' => '$2y$10$Gt68RT4joxsHeLh4p6WISuG3lz1ZhaTs9oUYgNhyuHrk7spRFY6b6','status' => NULL,'email_verified_at' => '2025-09-10 10:33:11','remember_token' => NULL,'created_at' => '2025-09-10 10:21:04','updated_at' => '2026-01-29 15:02:55'),
            array('business_id' => '1','staff_id' => '3','email' => 'waiter@acnoo.com','name' => 'Nusrat Jahan','role' => 'staff','phone' => '01822334455','gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => '{"dashboard":{"view":"1"},"quotations":{"view":"1","create":"1","update":"1","delete":"1"},"tables":{"view":"1","create":"1","update":"1","delete":"1"},"products":{"view":"1","view-all-data":"1"},"notification":{"view":"1"},"sales":{"view":"1","create":"1","update":"1","delete":"1"},"salesReport":{"view":"1"},"salesQuotationReport":{"view":"1"},"kot":{"view":"1"}}','fcm_token' => 'cBggS44YC2dXLv3vExCiGT:APA91bFU4SREyiAg3D9GMdudj4e16kaLRckbC-SHQy43U5INdZxDRaOc7P0KKsSpunYjsLghPOVgm0FSF-HWuhMctIgWl_JzY4u1ISqUrE4k13iZLsHpfjo','password' => '$2y$10$mj2h5Ri62bDAK64ZV7h3ceBwjIFbHzbih2HpUGOdKJjbG2uD9lsSu','status' => NULL,'email_verified_at' => '2025-09-10 10:33:11','remember_token' => NULL,'created_at' => '2025-09-10 10:18:46','updated_at' => '2026-01-31 15:22:50'),
            array('business_id' => '1','staff_id' => NULL,'email' => 'customer@acnoo.com','name' => 'Tyrell','role' => 'customer','phone' => NULL,'gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => NULL,'fcm_token' => NULL,'password' => '$2y$10$t49Fs.58jfF5Jnsyg1BZbuXRNqG.hxgwlmpGTwwv1LF8olWk6PV6y','status' => NULL,'email_verified_at' => '2025-11-17 10:33:35','remember_token' => NULL,'created_at' => '2025-11-17 10:32:41','updated_at' => '2025-11-17 10:33:35'),
            array('business_id' => '1','staff_id' => '10','email' => 'chef2@acnoo.com','name' => 'Hridhoy Hasan','role' => 'chef','phone' => '01523985647','gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => NULL,'fcm_token' => NULL,'password' => '$2y$10$IS0AXfaPpbAASi5HFG5xku.qCV6246RjVigCmpwiITnoWBWwY7jtq','status' => NULL,'email_verified_at' => NULL,'remember_token' => NULL,'created_at' => '2026-01-20 04:00:52','updated_at' => '2026-01-20 04:00:52'),
            array('business_id' => '1','staff_id' => '11','email' => 'kitchen@acnoo.com','name' => 'Harry Mark','role' => 'kitchen','phone' => '0635333388','gender' => NULL,'image' => NULL,'lang' => NULL,'visibility' => '{"dashboard":{"view":"1"},"ingredients":{"view":"1","create":"1","update":"1","delete":"1"},"units":{"view":"1","create":"1","update":"1","delete":"1"},"products":{"view":"1","create":"1","view-all-data":"1"},"categories":{"view":"1","create":"1","update":"1","delete":"1"},"menus":{"view":"1","create":"1","update":"1","delete":"1"},"modifierGroups":{"view":"1","create":"1","update":"1","delete":"1"},"itemModifiers":{"view":"1","create":"1","update":"1","delete":"1"},"kot":{"view":"1","update":"1"},"kotReport":{"view":"1"}}','fcm_token' => NULL,'password' => '$2y$10$BaEyKpi1RQ6EbmsODdxAP.szI9iNNZO8WYvfguCKGiXfLnFb.n7XO','status' => NULL,'email_verified_at' => NULL,'remember_token' => NULL,'created_at' => '2026-02-02 11:46:32','updated_at' => '2026-02-02 11:46:32')
        );

        User::insert($users);
    }
}
