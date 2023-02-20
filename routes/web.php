<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminColeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/profile', [DashboardController::class, 'show'])->name('dashboard.profile');
    Route::get('/dashboard/getProfile', [DashboardController::class, 'getProfile']);
    Route::post('/dashboard/putProfile', [DashboardController::class, 'setProfile']);

    /* Modulo Categorias */
    Route::get('/categorias', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::post('/categories/setCategoria', [CategoryController::class, 'setCategory']);
    Route::get('/categories/getCategory/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/categories/status/{id}', [CategoryController::class, 'setStatus']);
    Route::post('/categories/select', [CategoryController::class, 'getSelectCategorys']);
    Route::post('/categories/report', [CategoryController::class, 'getReport']);

    /* Modulo Productos */
    Route::get('/productos', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/products/setProduct', [ProductController::class, 'setProduct']);
    Route::get('/products/getProduct/{id}', [ProductController::class, 'getProduct']);
    Route::post('/products/status/{id}', [ProductController::class, 'setStatus']);
    Route::post('/products/report', [ProductController::class, 'getReport']);

    /* Modulo Administrador-Colegio */
    Route::get('/admin-colegio', [AdminColeController::class, 'index']);
    Route::get('/adminsColleges', [AdminColeController::class, 'getAdmins']);
    Route::post('/adminsColleges/setAdmin', [AdminColeController::class, 'setAdmin']);
    Route::get('/adminsColleges/getAdmin/{id}', [AdminColeController::class, 'getAdmin']);
    Route::post('/adminsColleges/setCollege', [AdminColeController::class, 'setCollegeAdmin']);
    Route::post('/adminsColleges/status/{id}', [AdminColeController::class, 'setStatus']);
    Route::post('/adminsColleges/delCollege/{id}', [AdminColeController::class, 'deleteCollegeAdmin']);
    Route::post('/adminsColleges/report', [AdminColeController::class, 'getReport']);

    /* Modulo Colegios */
    Route::get('/colegios', [CollegeController::class, 'index']);
    Route::get('/colleges', [CollegeController::class, 'getColleges']);
    Route::post('/colleges/setCollege', [CollegeController::class, 'setCollege']);
    Route::get('/colleges/getCollege/{id}', [CollegeController::class, 'getCollege']);
    Route::post('/colleges/status/{id}', [CollegeController::class, 'setStatus']);
    Route::post('/colleges/select', [CollegeController::class, 'getSelectColleges']);
    Route::post('/colleges/report', [CollegeController::class, 'getReport']);

    /* Modulo Notificaciones */
    Route::get('/notificaciones', [NotificationController::class, 'index']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/setNotification', [NotificationController::class, 'setNotification']);
    Route::get('/notifications/getNotification/{id}', [NotificationController::class, 'getNotification']);
    Route::get('/notifications/questions/{id}', [NotificationController::class, 'getQuestionsNotification']);
    Route::get('/notifications/question/{id}', [NotificationController::class, 'getQuestion']);
    Route::post('/notifications/setQuestion', [NotificationController::class, 'setQuestion']);
    Route::post('/notifications/status/{id}', [NotificationController::class, 'setStatus']);
    Route::post('/notifications/questionDel/{id}', [NotificationController::class, 'deleteQuestion']);
    Route::post('/notifications/report', [NotificationController::class, 'getReport']);

    /* Modulo Acciones */
    Route::get('/acciones', [ActionController::class, 'index']);
    Route::get('/actions', [ActionController::class, 'getActions']);
    Route::post('/actions/setAction', [ActionController::class, 'setAction']);
    Route::get('/actions/getAction/{id}', [ActionController::class, 'getAction']);
    Route::post('/actions/status/{id}', [ActionController::class, 'setStatus']);
    Route::post('/actions/select/', [ActionController::class, 'getSelectActions']);
    Route::post('/actions/report/', [ActionController::class, 'getReport']);

    /* Modulo Cursos */
    Route::get('/cursos', [CourseController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'getCourses']);
    Route::post('/courses/setCourse', [CourseController::class, 'setCourse']);
    Route::get('/courses/getCourse/{id}', [CourseController::class, 'getCourse']);
    Route::post('/courses/status/{id}', [CourseController::class, 'setStatus']);
    Route::post('/courses/select/', [CourseController::class, 'getSelectCourses']);
    Route::post('/courses/report/', [CourseController::class, 'getReport']);

});