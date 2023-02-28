<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
                
        DB::table('purchases')->insert([
            [
                'id' => 1,
                'user_id' => 5,
                'product_id' => 1,
                'points' => 45,
                'stock' => 3,
                'created_at' => '2023-02-22 19:25:43',
                'updated_at' => '2023-02-23 19:55:56',
                'remember_token' => 'fhdhdfgdfh',
            ],
          [
                'id' => 2,
                'user_id' => 5,
                'product_id' => 2,
                'points' => 14,
                'stock' => 1,
                'created_at' => '2023-02-23 16:50:14',
                'updated_at' => '2023-02-23 17:04:18',
                'remember_token' => Str::random(10),
          ],
           [
                'id' => 3,
                'user_id' => 8,
                'product_id' => 1,
                'points' => 15,
                'stock' => 1,
                'created_at' => '2023-02-23 20:46:28',
                'updated_at' => '2023-02-23 20:46:28',
                'remember_token' => Str::random(10),
           ],
            [
                'id' => 4,
                'user_id' => 8,
                'product_id' => 2,
                'points' => 28,
                'stock' => 2,
                'created_at' => '2023-02-25 02:01:41',
                'updated_at' => '2023-02-25 13:38:53',
                'remember_token' => Str::random(10),
            ],
        ]);
        
        
    }
}