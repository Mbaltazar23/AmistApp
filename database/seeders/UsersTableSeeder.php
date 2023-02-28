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
                'remember_token' => 'NLFMaK0iht60CJlkkEhasPzJlnPgObTMT3EuQBgj0ulPhP7ASXcJ0xgQpmUF',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-27 14:28:32',
            ],
            [
                'id' => 2,
                'dni' => '9.504.262-4',
                'name' => 'Ali',
                'email' => 'Ilua45@gmail.com',
                'phone' => '+56953453453',
                'points' => 1,
                'password' => '$2y$10$ScCkiqE4n6ukw.xNCikPceKddTvuvuh3b0aLk/6JD4d96RG0ox.W.',
                'address' => '',
                'status' => 2,
                'remember_token' => 'wgMZCj3N6BndTMVE6BvHfmSTdF4hyiHwAvHbSrmhvqf8Vi5ViRMAVbmDeGHG',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-25 13:28:44',
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
                'remember_token' => '0Y5HD1Oz8SRA56T5tNSF79Y1xnN3Zj03UiB5aFtX3suJHOITBh4PZ0leq669',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-27 14:26:30',
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
                'remember_token' => null,
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 13:14:54',
            ],
            [
                'id' => 5,
                'dni' => '7.045.840-3',
                'name' => 'Liam',
                'email' => 'Liam@gmail.com',
                'phone' => '+56935235367',
                'points' => 85,
                'password' => '$2y$10$PvKthQ3kjP0WmPnVmfwiDesvffZEWB6L11tOZ2V9c4/MAfNW5tzLa',
                'address' => '',
                'status' => 1,
                'remember_token' => 'T36wjvz4sCdBDCEboFD4PqpMSFhrMfx14Vm7ynHKFuHunnDGlvlzYfA67Wkh',
                'created_at' => '2023-02-23 13:14:54',
                'updated_at' => '2023-02-23 20:27:01',
            ],
            [
                'id' => 6,
                'dni' => '6.575.675-6',
                'name' => 'Juanpa',
                'email' => 'Juanpa@gmail.com',
                'phone' => '+56953463463',
                'points' => 500,
                'password' => '$2y$10$jBcrnB5woPQyLw7fTzFbwup1id54BVeteZqKkK76Hc3TGGIbffoRa',
                'address' => '',
                'status' => 1,
                'remember_token' => 'SbTUZzdKeM',
                'created_at' => '2023-02-23 20:40:45',
                'updated_at' => '2023-02-23 20:40:45',
            ],
            [
                'id' => 8,
                'dni' => '4.082.201-1',
                'name' => 'Leo',
                'email' => 'Leo56@gmail.com',
                'phone' => '+56946346364',
                'points' => 57,
                'password' => '$2y$10$IJJ8oUFIH0L6kKhomH880efvLRN802f4kUpgFmhv95COCi3Xv.3je',
                'address' => '',
                'status' => 1,
                'remember_token' => 'facopLWlDkSMtcRuYjnLIItBB9ps15fAfdtpQWpUhi76P4Xg0lTs3vqeU9qa',
                'created_at' => '2023-02-23 20:42:25',
                'updated_at' => '2023-02-25 19:08:21',
            ],
            [
                'id' => 9,
                'dni' => '7.045.850-4',
                'name' => 'Yuni',
                'email' => 'Yuni@gmail.com',
                'phone' => '+56945346364',
                'points' => 100,
                'password' => '$2y$10$pXWXhoOIXKZu7lWro1cFC.LRuufW3FO22RYI5jWS/sRjV.237UYNi',
                'address' => '',
                'status' => 1,
                'remember_token' => 'yR8CGdloRS',
                'created_at' => '2023-02-23 20:44:15',
                'updated_at' => '2023-02-23 20:44:15',
            ],
        ]);

    }
}
