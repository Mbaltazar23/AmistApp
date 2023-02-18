<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
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
                'created_at' => '2023-02-17 03:33:43',
                'id' => 1,
                'message' => 'Bienvenido',
                'points' => 23,
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'type' => 'Video/Message',
                'updated_at' => '2023-02-17 03:33:43',
            ],
            [
                'created_at' => '2023-02-17 03:37:24',
                'id' => 2,
                'message' => 'Bienvenido',
                'points' => 34,
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'type' => 'Question',
                'updated_at' => '2023-02-17 03:37:24',
            ],
        ]);

    }
}
