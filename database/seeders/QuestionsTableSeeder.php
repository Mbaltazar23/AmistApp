<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'id' => 1,
                'notification_id' => 1,
                'remember_token' => null,
                'text_question' => 'Aqui Veras Esto',
                'created_at' => '2023-02-17 03:33:43',
                'updated_at' => '2023-02-17 03:33:43',
                'remember_token' => Str::random(10),

            ],
            [
                'id' => 2,
                'notification_id' => 2,
                'remember_token' => null,
                'text_question' => 'Le Gustaria Comer',
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 19:13:54',
                'remember_token' => Str::random(10),

            ],
        ]);
    }
}
