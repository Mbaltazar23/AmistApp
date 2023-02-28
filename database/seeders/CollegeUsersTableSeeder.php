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
                'created_at' => '2023-02-23 13:14:55',
                'updated_at' => '2023-02-23 13:14:55',
                'remember_token' => 'CmpDrxuHKY',
            ],
            [
                'id' => 3,
                'college_id' => 1,
                'user_id' => 4,
                'created_at' => '2023-02-23 13:14:55',
                'updated_at' => '2023-02-23 13:14:55',
                'remember_token' => null,
            ],
            [
                'id' => 4,
                'college_id' => 1,
                'user_id' => 5,
                'created_at' => '2023-02-23 13:14:55',
                'updated_at' => '2023-02-23 13:14:55',
                'remember_token' => null,
            ],
            [
                'id' => 5,
                'college_id' => 2,
                'user_id' => 2,
                'created_at' => '2023-02-23 20:35:18',
                'updated_at' => '2023-02-23 20:35:18',
                'remember_token' => null,
            ],
            [
                'id' => 6,
                'college_id' => 2,
                'user_id' => 6,
                'created_at' => '2023-02-23 20:40:45',
                'updated_at' => '2023-02-23 20:40:45',
                'remember_token' => 'PGqZuxk25v',
            ],
            [
                'id' => 7,
                'college_id' => 2,
                'user_id' => 9,
                'created_at' => '2023-02-23 20:44:15',
                'updated_at' => '2023-02-23 20:44:15',
                'remember_token' => null,
            ],
            [
                'id' => 8,
                'college_id' => 2,
                'user_id' => 8,
                'created_at' => '2023-02-25 01:52:31',
                'updated_at' => '2023-02-25 01:52:31',
                'remember_token' => null,
            ],
        ]);

    }
}
