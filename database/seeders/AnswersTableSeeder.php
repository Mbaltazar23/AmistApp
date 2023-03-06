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
                'id' => 1,
                'text_answer' => 'Ya entonces mir',
                'question_id' => 1,
                'advice' => 'Todo est para hoy',
                'created_at' => '2023-02-17 03:33:44',
                'updated_at' => '2023-02-17 15:40:53',
                'remember_token' => 'AZAwvUOCzB',
            ],
            [
                'id' => 2,
                'text_answer' => 'Si quiero comer',
                'question_id' => 2,
                'advice' => 'Ya entonces mira esto',
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 03:37:24',
                'remember_token' => 'Z5KlddI1xU',
            ],
            [
                'id' => 3,
                'text_answer' => 'Yo quiero mimir',
                'question_id' => 2,
                'advice' => 'Ya entonces te dejaremos solito',
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 03:37:24',
                'remember_token' => 'JArenmb5Xc',
            ],
            [
                'id' => 4,
                'text_answer' => 'Yo quiero corres',
                'question_id' => 2,
                'advice' => 'Okis',
                'created_at' => '2023-02-17 03:37:24',
                'updated_at' => '2023-02-17 03:37:24',
                'remember_token' => 'c1r5ZpHawZ',
            ],
            [
                'id' => 5,
                'text_answer' => 'La respuesta es 4',
                'question_id' => 3,
                'advice' => 'Bien se gano un premio',
                'created_at' => '2023-02-28 15:44:32',
                'updated_at' => '2023-02-28 15:44:32',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 6,
                'text_answer' => 'La respuesta es 6',
                'question_id' => 3,
                'advice' => 'Se gano un dia libre',
                'created_at' => '2023-02-28 15:44:32',
                'updated_at' => '2023-02-28 15:44:32',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 7,
                'text_answer' => 'La respuesta es 8',
                'question_id' => 3,
                'advice' => 'Se gano un dia laburioso',
                'created_at' => '2023-02-28 15:44:32',
                'updated_at' => '2023-02-28 15:44:32',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 8,
                'text_answer' => 'La respuesta es 10',
                'question_id' => 4,
                'advice' => 'Bien, se gano una cerez',
                'created_at' => '2023-02-28 15:45:25',
                'updated_at' => '2023-02-28 15:45:25',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 9,
                'text_answer' => 'La respuesta es 12',
                'question_id' => 4,
                'advice' => 'La respuesta es 8',
                'created_at' => '2023-02-28 15:45:25',
                'updated_at' => '2023-02-28 15:45:25',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 10,
                'text_answer' => 'La respuesta es 8',
                'question_id' => 4,
                'advice' => 'Bien, pudo intentarlo',
                'created_at' => '2023-02-28 15:45:25',
                'updated_at' => '2023-02-28 15:45:25',
                'remember_token' => Str::random(10),
            ],
        ]);
           
    }
}