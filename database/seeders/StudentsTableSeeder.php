<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('students')->insert([
            ['id' => 3,
                'course_id' => 1,
                'user_id' => 5,
                'created_at' => '2023-02-21 23:56:25',
                'updated_at' => '2023-02-21 23:56:25',
            ],
            [
                'id' => 4,
                'course_id' => 3,
                'user_id' => 8,
                'created_at' => '2023-02-28 00:30:20',
                'updated_at' => '2023-02-22 00:00:00',
            ],
            [
                'id' => 5,
                'course_id' => 3,
                'user_id' => 9,
                'created_at' => '2023-02-23 20:44:15',
                'updated_at' => '2023-02-23 20:44:15',
            ],
        ]);

    }
}
