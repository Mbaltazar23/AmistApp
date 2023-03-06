<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('user_roles')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'role' => 'Administrador',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => 'W7UkNzbPA1',
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'role' => 'Administrador de Colegio',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => 'uTgZF08xcM',
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'role' => 'Administrador de Colegio',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => 'z0PxNHgTKB',
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'role' => 'Profesor',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'role' => 'Alumno',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 6,
                'user_id' => 6,
                'role' => 'Profesor',
                'created_at' => '2023-02-23 20:40:45',
                'updated_at' => '2023-02-23 20:40:45',
                'remember_token' => '0gjcjfy1XZ',
            ],
            [
                'id' => 8,
                'user_id' => 8,
                'role' => 'Alumno',
                'created_at' => '2023-02-23 20:42:25',
                'updated_at' => '2023-02-23 20:42:25',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 9,
                'user_id' => 9,
                'role' => 'Alumno',
                'created_at' => '2023-02-23 20:44:15',
                'updated_at' => '2023-02-23 20:44:15',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 11,
                'user_id' => 11,
                'role' => 'Alumno',
                'created_at' => '2023-03-01 16:11:19',
                'updated_at' => '2023-03-01 16:11:19',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 12,
                'user_id' => 12,
                'role' => 'Alumno',
                'created_at' => '2023-03-01 16:37:55',
                'updated_at' => '2023-03-01 16:37:55',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 13,
                'user_id' => 14,
                'role' => 'Profesor',
                'created_at' => '2023-03-01 20:14:57',
                'updated_at' => '2023-03-01 20:14:57',
                'remember_token' => '09DOqKyaKv',
            ],
            [
                'id' => 14,
                'user_id' => 15,
                'role' => 'Alumno',
                'created_at' => '2023-03-01 20:16:59',
                'updated_at' => '2023-03-01 20:16:59',
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 15,
                'user_id' => 16,
                'role' => 'Profesor',
                'created_at' => '2023-03-02 20:21:02',
                'updated_at' => '2023-03-02 20:21:02',
                'remember_token' => 'XM20BZxlpT',
            ],
        ]);

    }
}
