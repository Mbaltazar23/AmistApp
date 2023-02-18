<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            [
                'user_id' => 1,
                'role' => 'Administrador',
                'remember_token' =>  Str::random(10),
            ],
            [
                'user_id' => 2,
                'role' => 'Administrador de Colegio',
                'remember_token' =>  Str::random(10),

            ],
            [
                'user_id' => 3,
                'role' => 'Administrador de Colegio',
                'remember_token' =>  Str::random(10),
            ],
        ]);
        
    }
}
