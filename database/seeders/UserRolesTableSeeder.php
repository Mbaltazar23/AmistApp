<?php

namespace Database\Seeders;

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
            ],
            [
                'user_id' => 2,
                'role' => 'Administrador de Colegio',
            ],
            [
                'user_id' => 3,
                'role' => 'Administrador de Colegio',
            ],
        ]);
    }
}
