<?php

namespace Database\Seeders;

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
                'points' => 15,
                'created_at' => '2023-02-22 19:25:43',
                'updated_at' => '2023-02-22 19:25:43',
                'remember_token' => 'fhdhdfgdfh',
            ],
        ]);  
    }
}