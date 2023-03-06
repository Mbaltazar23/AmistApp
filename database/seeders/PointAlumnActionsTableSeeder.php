<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointAlumnActionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
                
        DB::table('point_alumn_actions')->insert([
            [
                'id' => 1,
                'points' => 48,
                'user_send_id' => 6,
                'user_recept_id' => 8,
                'action_id' => 2,
                'created_at' => '2023-02-28 02:22:27',
                'updated_at' => '2023-02-28 17:45:03',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 2,
                'points' => 48,
                'user_send_id' => 4,
                'user_recept_id' => 5,
                'action_id' => 2,
                'created_at' => '2023-02-28 18:17:03',
                'updated_at' => '2023-03-01 01:21:20',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 3,
                'points' => 16,
                'user_send_id' => 6,
                'user_recept_id' => 9,
                'action_id' => 2,
                'created_at' => '2023-03-01 00:48:22',
                'updated_at' => '2023-03-01 00:48:22',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 4,
                'points' => 16,
                'user_send_id' => 5,
                'user_recept_id' => 11,
                'action_id' => 3,
                'created_at' => '2023-03-01 16:27:08',
                'updated_at' => '2023-03-01 16:27:08',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 5,
                'points' => 30,
                'user_send_id' => 8,
                'user_recept_id' => 9,
                'action_id' => 1,
                'created_at' => '2023-03-01 17:17:21',
                'updated_at' => '2023-03-01 19:40:12',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 6,
                'points' => 16,
                'user_send_id' => 4,
                'user_recept_id' => 12,
                'action_id' => 2,
                'created_at' => '2023-03-01 17:22:38',
                'updated_at' => '2023-03-01 17:22:38',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 7,
                'points' => 32,
                'user_send_id' => 14,
                'user_recept_id' => 15,
                'action_id' => 2,
                'created_at' => '2023-03-01 20:17:35',
                'updated_at' => '2023-03-02 00:05:12',
                'remember_token' => Str::random(10),
            ],
        ]);
        
        
    }
}