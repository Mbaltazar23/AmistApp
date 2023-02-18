<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
            
        DB::table('answers')->insert([
         [
                'advice' => 'Todo est para hoy',
                'created_at' => '2023-02-17 03:33:44',
                'id' => 1,
                'question_id' => 1,
                'remember_token' =>  Str::random(10),
                'text_answer' => 'Ya entonces mir',
                'updated_at' => '2023-02-17 15:40:53',
         ],
           [
                'advice' => 'Ya entonces mira esto',
                'created_at' => '2023-02-17 03:37:24',
                'id' => 2,
                'question_id' => 2,
                'remember_token' =>  Str::random(10),
                'text_answer' => 'Si quiero comer',
                'updated_at' => '2023-02-17 03:37:24',
           ],
            [
                'advice' => 'Ya entonces te dejaremos solito',
                'created_at' => '2023-02-17 03:37:24',
                'id' => 3,
                'question_id' => 2,
                'remember_token' =>  Str::random(10),
                'text_answer' => 'Yo quiero mimir',
                'updated_at' => '2023-02-17 03:37:24',
            ],
            [
                'advice' => 'Okis',
                'created_at' => '2023-02-17 03:37:24',
                'id' => 4,
                'question_id' => 2,
                'remember_token' =>  Str::random(10),
                'text_answer' => 'Yo quiero corres',
                'updated_at' => '2023-02-17 03:37:24',
            ],
        ]);
        
        
    }
}