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
                'address' => NULL,
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
                'address' => NULL,
                'created_at' => '2023-02-20 19:34:35',
                'updated_at' => '2023-02-20 19:34:35',
                'status' => 1,
                'remember_token' => 'KLoBFiHW9x',
            ],
        ]);    
    }
}