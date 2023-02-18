<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'dni' => '19.492.929-5',
                'name' => 'Jose',
                'email' => 'Jose45@gmail.com',
                'phone' => '+56987453423',
                'password' =>  bcrypt('AmistApp.'),
                'address' => 'Temuco en casita',
                'status' => 1,
                'remember_token' =>  Str::random(10),

            ],
            [
                'dni' => '19.493.544-3',
                'name' => 'Iluas',
                'email' => 'Ilua45@gmail.com',
                'phone' => '+56953453453',
                'password' => bcrypt('AmistApp.'),
                'address' => '',
                'status' => 1,
                'remember_token' =>  Str::random(10),
            ],
            [
                'dni' => '14.354.656-5',
                'name' => 'Aqua',
                'email' => 'Aqua34@gmail.com',
                'phone' => '+56945353464',
                'password' => bcrypt('AmistApp.'),
                'address' => 'Las petunias',
                'status' => 2,
                'remember_token' =>  Str::random(10),
            ],
        ]);
    }
}
