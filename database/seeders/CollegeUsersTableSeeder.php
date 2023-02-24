<?php

namespace Database\Seeders;

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
                'id' => 2,
                'college_id' => 1,
                'user_id' => 3,
                'remember_token' => 'CmpDrxuHKY',
            ],
            [
                'id' => 3,
                'college_id' => 1,
                'user_id' => 4,
                'remember_token' => NULL,
            ],
            [
                'id' => 4,
                'college_id' => 1,
                'user_id' => 5,
                'remember_token' => NULL,
            ],
        ]);
        
        
    }
}