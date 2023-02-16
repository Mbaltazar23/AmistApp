<?php

use App\Http\Controllers\AdminColeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DashboardController;
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
    
    /* Modulo Categorias */
    Route::get('/categorias', [CategoryController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::post('/categories/setCategoria', [CategoryController::class, 'setCategory']);
    Route::get('/categories/getCategory/{id}', [CategoryController::class, 'getCategory']);
    Route::post('/categories/status/{id}', [CategoryController::class, 'setStatus']);
    Route::post('/categories/select', [CategoryController::class, 'getSelectCategorys']);
    
    /* Modulo Productos */
    Route::get('/productos', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/products/setProduct', [ProductController::class, 'setProduct']);
    Route::get('/products/getProduct/{id}', [ProductController::class, 'getProduct']);
    Route::post('/products/status/{id}', [ProductController::class, 'setStatus']);

    /* Modulo Administrador-Colegio */
    Route::get('/admin-colegio', [AdminColeController::class, 'index']);
    Route::get('/adminsColleges', [AdminColeController::class, 'getAdmins']);
    Route::post('/adminsColleges/setAdmin', [AdminColeController::class, 'setAdmin']);
    Route::get('/adminsColleges/getAdmin/{id}', [AdminColeController::class, 'getAdmin']);
    Route::post('/adminsColleges/setCollege', [AdminColeController::class, 'setCollegeAdmin']);
    Route::post('/adminsColleges/status/{id}', [AdminColeController::class, 'setStatus']);
    Route::post('/adminsColleges/delCollege/{id}', [AdminColeController::class, 'deleteCollegeAdmin']);
    Route::post('/adminsColleges/report', [AdminColeController::class, 'getReport']);

    /*Modulo Colegios */
    Route::get('/colegios', [CollegeController::class, 'index']);

    Route::post('/colleges/select', [CollegeController::class, 'getSelectColleges']);

});
