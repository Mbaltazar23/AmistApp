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
                'image'=> 'ct_d12117a9024926464a903859b13eb3e2.jpg',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:11:19',
            ],
            [
                'created_at' => '2023-02-16 05:11:28',
                'id' => 2,
                'name' => 'Gaseosa',
                'image'=> 'ct_8387b2b0c0e4d8f05701a2334bcb131f.jpg',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:11:28',
            ],
            [
                'created_at' => '2023-02-16 05:27:46',
                'id' => 3,
                'name' => 'Frituras',
                'image' => 'ct_8e052fa3a5563493979bd69b13707631.jpg',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:46',
            ],
            [
                'created_at' => '2023-02-16 05:27:52',
                'id' => 4,
                'name' => 'Lacteos',
                'image' =>'ct_dd4cbea548c97d3895a46f6bc52702e4.jpg',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:52',
            ],
            [
                'created_at' => '2023-02-16 05:27:59',
                'id' => 5,
                'name' => 'Utensilios',
                'image' => 'ct_138f3d440f57f5d7838c4f4aa0f924b1.jpg',
                'remember_token' =>  Str::random(10),
                'status' => 1,
                'updated_at' => '2023-02-16 05:27:59',
            ],
        ]);

    }
}
