<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
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
                'address' => '',
                'created_at' => '2023-02-16 05:20:38',
                'dni' => '9.656.456-5',
                'id' => 1,
                'name' => 'San Marcos',
                'phone' => '+56945436346',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:20:38',
            ],
        ]);

    }
}
