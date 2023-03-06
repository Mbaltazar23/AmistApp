<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        
        DB::table('actions')->insert([
            [
                'id' => 1,
                'name' => 'Tomar Pastillas',
                'type' => 'Alumno',
                'points' => 15,
                'status' => 1,
                'created_at' => '2023-02-28 00:54:38',
                'updated_at' => '2023-02-28 00:54:38',
                'remember_token' => 'UMoPmJ92VX',
            ],
            [
                'id' => 2,
                'name' => 'Dar Decimas',
                'type' => 'Profesor',
                'points' => 16,
                'status' => 1,
                'created_at' => '2023-02-28 00:55:50',
                'updated_at' => '2023-02-28 00:55:50',
                'remember_token' => 'RwCc4wQquR',
            ],
            [
                'id' => 3,
                'name' => 'Comer Avichuelas',
                'type' => 'Alumno',
                'points' => 16,
                'status' => 1,
                'created_at' => '2023-03-01 15:24:50',
                'updated_at' => '2023-03-01 15:24:50',
                'remember_token' => 'RK3uEmTZKn',
            ],
            [
                'id' => 4,
                'name' => 'Correr',
                'type' => 'Alumno',
                'points' => 15,
                'status' => 1,
                'created_at' => '2023-03-01 15:25:24',
               'updated_at' => '2023-03-01 15:25:24',
                'remember_token' => '0lvWMUl7vc',
            ],
        ]);
        
        
    }
}