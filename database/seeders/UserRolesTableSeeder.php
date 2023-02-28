<?php

namespace Database\Seeders;

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
            ['id' => 3,
                'user_id' => 3,
                'role' => 'Administrador de Colegio',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => 'z0PxNHgTKB',
            ],
            ['id' => 4,
                'user_id' => 4,
                'role' => 'Profesor',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => null,
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'role' => 'Alumno',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
                'remember_token' => null,
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
                'remember_token' => null,
            ],
            [
                'id' => 9,
                'user_id' => 9,
                'role' => 'Alumno',
                'created_at' => '2023-02-23 20:44:15',
                'updated_at' => '2023-02-23 20:44:15',
                'remember_token' => null,
            ],
        ]);

    }
}
