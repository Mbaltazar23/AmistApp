<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        
        DB::table('courses')->insert([
            [
                'id' => 1,
                'name' => '4to Medio',
                'section' => 'C',
                'college_id' => 1,
                'created_at' => '2023-02-20 19:25:45',
                'updated_at' => '2023-02-20 19:34:25',
                'status' => 1,
                'remember_token' => 'aNPaET9QyQ',
            ],
            [
                'id' => 2,
                'name' => '3 Medip',
                'section' => 'B',
                'college_id' => 1,
                'created_at' => '2023-02-20 19:34:35',
                'updated_at' => '2023-02-20 19:34:35',
                'status' => 1,
                'remember_token' => 'KLoBFiHW9x',
            ],
            [
                'id' => 3,
                'name' => '4 Medio',
                'section' => 'C',
                'college_id' => 2,
                'created_at' => '2023-02-23 20:39:29',
                'updated_at' => '2023-02-23 20:39:29',
                'status' => 1,
                'remember_token' => 'adUzgkvnwJ',
            ],
            [
                'id' => 4,
                'name' => '3 Medio',
                'section' => 'D',
                'college_id' => 2,
                'created_at' => '2023-03-01 20:14:20',
                'updated_at' => '2023-03-01 20:14:20',
                'status' => 1,
                'remember_token' => 'WFqj1Gx3vg',
            ],
            [
                'id' => 5,
                'name' => '1 Medio',
                'section' => 'C',
                'college_id' => 2,
                'created_at' => '2023-03-02 16:06:22',
                'updated_at' => '2023-03-02 16:06:22',
                'status' => 1,
                'remember_token' => 'l0L7hBWjyC',
            ],
        ]);
        
        
    }
}