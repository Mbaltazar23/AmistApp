<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'text_question' => 'Aqui Veras Esto',
                'notification_id' => 1,
                'created_at' => '2023-02-17 03:33:43',
                'updated_at' => '2023-02-17 03:33:43',
                'remember_token' => 'IfIBMGEsGr',
            ],
            [
                'id' => 2,
                'text_question' => 'Le Gustaria Comer',
                'notification_id' => 2,
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 19:13:54',
                'remember_token' => 'GGy0l2iOkX',
            ],
            [
                'id' => 3,
                'text_question' => 'Cuanto Es 3 +3',
                'notification_id' => 3,
                'created_at' => '2023-02-28 15:44:32',
                'updated_at' => '2023-02-28 15:44:32',
                'remember_token' => NULL,
            ],
            [
                'id' => 4,
                'text_question' => 'Cuanto Es 5 + 5',
                'notification_id' => 3,
                'created_at' => '2023-02-28 15:45:25',
                'updated_at' => '2023-02-28 15:45:25',
                'remember_token' => NULL,
            ],
        ]);        
    }
}