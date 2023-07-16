<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(AnswersTableSeeder::class);
        $this->call(CollegesTableSeeder::class);
        $this->call(CollegeUsersTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(PurchasesTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(CourseTeacherSeeder::class);
        $this->call(ActionsTableSeeder::class);
        $this->call(PointAlumnActionsTableSeeder::class);
    }
}
