<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegeUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {    
        DB::table('college_users')->insert([
           [
                'college_id' => 1,
                'created_at' => '2023-02-17 15:52:39',
                'id' => 2,
                'remember_token' =>  Str::random(10),
                'updated_at' => '2023-02-17 15:52:39',
                'user_id' => 3,
           ],
        ]);
    }
}