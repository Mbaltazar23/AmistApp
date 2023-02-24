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
                'remember_token' => 'W7UkNzbPA1',
           ],
           [
                'id' => 2,
                'user_id' => 2,
                'role' => 'Administrador de Colegio',
                'remember_token' => 'uTgZF08xcM',
           ],
           [
                'id' => 3,
                'user_id' => 3,
                'role' => 'Administrador de Colegio',
                'remember_token' => 'z0PxNHgTKB',
           ],
            [
                'id' => 4,
                'user_id' => 4,
                'role' => 'Profesor',
                'remember_token' => NULL,
            ],
           [
                'id' => 5,
                'user_id' => 5,
                'role' => 'Alumno',
                'remember_token' => NULL,
           ],
        ]);
        
        
    }
}