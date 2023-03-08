<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
                
        DB::table('notifications')->insert([
            [
                'id' => 1,
                'message' => 'Bienvenido',
                'type' => 'Video/Message',
                'points' => 23,
                'status' => 1,
                'expiration_date'=> null,
                'created_at' => '2023-02-17 03:33:43',
                'updated_at' => '2023-02-17 03:33:43',
                'remember_token' => 'HxMdFb2EuV',
            ],
            [
                'id' => 2,
                'message' => 'Bienvenido',
                'type' => 'Question',
                'points' => 34,
                'status' => 1,
                'expiration_date'=> null,
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 03:37:24',
                'remember_token' => '1EqBPYGtj2',
            ],
            [
                'id' => 3,
                'message' => 'Pregutna X1',
                'type' => 'Question',
                'points' => 17,
                'status' => 1,
                'expiration_date'=> null,
                'created_at' => '2023-02-28 15:44:32',
                'updated_at' => '2023-02-28 17:38:42',
                'remember_token' => 'ejznEw4BxU',
            ],
        ]);
    }
}