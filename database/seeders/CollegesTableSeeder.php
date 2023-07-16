<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {        
        DB::table('colleges')->insert([
            [
                'id' => 1,
                'name' => 'San Marcos',
                'dni' => '9.656.456-5',
                'address' => '',
                'phone' => '+56945436346',
                'stock_alumns'=> 20,
                'status' => 1,
                'created_at' => '2023-02-16 05:20:38',
                'updated_at' => '2023-02-16 05:20:38',
                'remember_token' => 'KUbRjjkBC7',
            ],
            [
                'id' => 2,
                'name' => 'Santa Monica',
                'dni' => '3.553.454-5',
                'address' => '',
                'phone' => '+56946346346',
                'stock_alumns'=> 40,
                'status' => 1,
                'created_at' => '2023-02-23 20:33:41',
                'updated_at' => '2023-02-23 20:33:41',
                'remember_token' => 'jdkzlfc5ea',
            ],
        ]);
        
        
    }
}