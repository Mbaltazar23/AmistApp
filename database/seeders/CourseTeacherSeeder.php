<?php

namespace Database\Seeders;
use Illuminate\Support\Str;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //course_teachers
        DB::table('course_teachers')->insert([
            [
                "id" => 1,
                "teacher_id" => 1,
                "course_id" =>1,
                'created_at' => '2023-07-16 00:20:38',
                'updated_at' => '2023-07-16 07:20:38',
            ],
            [
                "id" => 2,
                "teacher_id" => 2,
                "course_id" =>1,
                'created_at' => '2023-07-16 00:20:38',
                'updated_at' => '2023-07-16 07:20:38',
            ],
            [
                "id" => 3,
                "teacher_id" => 1,
                "course_id" =>2,
                'created_at' => '2023-07-15 00:20:38',
                'updated_at' => '2023-07-15 14:20:38',
            ],
            [
                "id" => 4,
                "teacher_id" => 2,
                "course_id" =>3,
                'created_at' => '2023-07-15 20:05:38',
                'updated_at' => '2023-07-16 07:20:38',
            ],
            [
                "id" => 5,
                "teacher_id" => 3,
                "course_id" => 2,
                'created_at' => '2023-07-15 20:05:38',
                'updated_at' => '2023-07-16 07:20:38',
            ]
        ]);
    }
}
