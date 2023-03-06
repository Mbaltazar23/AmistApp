<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'updated_at' => '2023-03-02 20:47:00',
                'remember_token' => 'CmpDrxuHKY',
            ],
            [
                'id' => 3,
                'college_id' => 1,
                'user_id' => 4,
                'created_at' => '2023-02-23 13:14:55',
                'updated_at' => '2023-02-23 13:14:55',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 4,
                'college_id' => 1,
                'user_id' => 5,
                'created_at' => '2023-02-23 13:14:55',
                'updated_at' => '2023-02-23 13:14:55',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 5,
                'college_id' => 2,
                'user_id' => 2,
                'created_at' => '2023-02-23 20:35:18',
                'updated_at' => '2023-02-23 20:35:18',
                'remember_token' => Str::random(10),
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
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 8,
                'college_id' => 2,
                'user_id' => 8,
                'created_at' => '2023-02-25 01:52:31',
                'updated_at' => '2023-02-25 01:52:31',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 9,
                'college_id' => 1,
                'user_id' => 11,
                'created_at' => '2023-03-01 16:11:19',
                'updated_at' => '2023-03-01 16:11:19',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 10,
                'college_id' => 1,
                'user_id' => 12,
                'created_at' => '2023-03-01 16:37:55',
                'updated_at' => '2023-03-01 16:37:55',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 11,
                'college_id' => 2,
                'user_id' => 14,
                'created_at' => '2023-03-01 20:14:57',
                'updated_at' => '2023-03-01 20:14:57',
                'remember_token' => 'BBQ3fvbu0X',
            ],
            [
                'id' => 12,
                'college_id' => 2,
                'user_id' => 15,
                'created_at' => '2023-03-01 20:16:59',
                'updated_at' => '2023-03-01 20:16:59',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 13,
                'college_id' => 1,
                'user_id' => 16,
                'created_at' => '2023-03-02 20:21:02',
                'updated_at' => '2023-03-02 20:21:02',
                'remember_token' => 'oqeuD4ayJ2',
            ],
        ]);

    }
}
