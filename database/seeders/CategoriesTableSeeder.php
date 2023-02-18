<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('categories')->insert([
            [
                'created_at' => '2023-02-16 05:11:19',
                'id' => 1,
                'name' => 'Papas',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:11:19',
            ],
            [
                'created_at' => '2023-02-16 05:11:28',
                'id' => 2,
                'name' => 'Gaseosa',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:11:28',
            ],
            [
                'created_at' => '2023-02-16 05:27:46',
                'id' => 3,
                'name' => 'Frituras',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:46',
            ],
            [
                'created_at' => '2023-02-16 05:27:52',
                'id' => 4,
                'name' => 'Lacteos',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:52',
            ],
            [
                'created_at' => '2023-02-16 05:27:59',
                'id' => 5,
                'name' => 'Utensilios',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:59',
            ],
        ]);

    }
}
