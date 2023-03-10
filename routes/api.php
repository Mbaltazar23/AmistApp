<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/alumns', [TeacherController::class, 'index']);
Route::get('/catalog', [ProductController::class, 'indexCat']);
Route::get('/catalog-college', [ProductController::class, 'indexCatCollege']);
Route::get('/catalog-alumn', [ProductController::class, 'indexCatAlum']);
Route::post('/catalog/setPurchase/{id}', [ProductController::class, 'store']);
Route::delete('/catalog/deletePurchase/{id}', [ProductController::class, 'delete']);
Route::get('/points/alumns', [StudentController::class, 'index']);
Route::get('/points/getAlum/{id}',[StudentController::class, 'show']);
Route::post('/points/setPointAction', [StudentController::class, 'store']);
