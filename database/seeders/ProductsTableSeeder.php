<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        
        DB::table('products')->insert([
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'lays',
                'image' => 'prod_c6702d24a6deafc9b95a6c281838ab12.jpg',
                'points' => 15,
                'stock' => 10,
                'remember_token' => 'rMPyS3N312',
            ],
           [
                'id' => 2,
                'category_id' => 2,
                'name' => 'pepsi',
                'image' => 'prod_088321425d4b21dbe3595c2b9a6ef1a4.jpg',
                'points' => 14,
                'stock' => 10,
                'remember_token' => 'OXYEJrswsL',
           ],
        ]);
        
        
    }
}