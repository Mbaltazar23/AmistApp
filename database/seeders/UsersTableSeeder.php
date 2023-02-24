<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
                
        DB::table('users')->insert([
            [
                'id' => 1,
                'dni' => '19.492.929-5',
                'name' => 'Jose',
                'email' => 'Jose45@gmail.com',
                'phone' => '+56987453423',
                'points' => 1,
                'password' => '$2y$10$Jh2T.ZrAHhNVaF5pHyh/Ou6g6ZGvxsc5UhtppcStqj/k8ixmuxlEq',
                'address' => 'Temuco en casita',
                'status' => 1,
                'remember_token' => 'tC0BeeCPVdfNj6b4f02n1P3A3n3S02Dp4bOSSAnwkg0XohrikJKodQNhkYQ2',
            ],
            [
                'id' => 2,
                'dni' => '19.493.544-3',
                'name' => 'Iluas',
                'email' => 'Ilua45@gmail.com',
                'phone' => '+56953453453',
                'points' => 1,
                'password' => '$2y$10$nQNU0EvHN/H92j5KOcrfRuWpAJndtrBu2XpxZsyMg2veoor4SoDde',
                'address' => '',
                'status' => 1,
                'remember_token' => '4bN2vtaSHX',
            ],
           [
                'id' => 3,
                'dni' => '14.354.656-5',
                'name' => 'Aqua',
                'email' => 'Aqua34@gmail.com',
                'phone' => '+56945353464',
                'points' => 1,
                'password' => '$2y$10$IC46z6S.nzA1nbw0kMRd9enmGjd3tP2GDTJaoDWVLNlRmxwksGKLa',
                'address' => 'Las petunias',
                'status' => 2,
                'remember_token' => '6b0fgHeU10Mwu1cgI35yksX5d8y32z57DNI8cvsiJAhRrgAa2sDQ7zapvQeF',
           ],
           [
                'id' => 4,
                'dni' => '4.634.645-4',
                'name' => 'Juan',
                'email' => 'Juan@gmail.com',
                'phone' => '+56946346464',
                'points' => 500,
                'password' => '$2y$10$DHCrGJcEyr3yf5F90pVZ2OFC0iXr5UScwtA//pZw8dQxIaMihx0aW',
                'address' => '',
                'status' => 1,
                'remember_token' => NULL,
           ],
           [
                'id' => 5,
                'dni' => '7.045.840-3',
                'name' => 'Liam',
                'email' => 'Liam@gmail.com',
                'phone' => '+56935235367',
                'points' => 100,
                'password' => '$2y$10$PvKthQ3kjP0WmPnVmfwiDesvffZEWB6L11tOZ2V9c4/MAfNW5tzLa',
                'address' => '',
                'status' => 1,
                'remember_token' => 'dx95FtdbhWXx2tDEgHlAJXhgwer7pcIL4lch9rqBj3ZMPVACExTPvuqkORvf',
           ],
        ]);
        
        
    }
}