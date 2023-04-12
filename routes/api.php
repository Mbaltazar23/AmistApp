<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
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

Route::fallback(function () {
    return response()->json(['error' => 'Unauthorized'], 401);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset', [AuthController::class, 'getEmail']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

Route::middleware('auth.api')->group(function () {
    /* Modulo Usuario (obtener sus datos y actualizarlos) */
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::post('/putUser', [AuthController::class, 'update']);
    /* Menu del Dashboard*/
    Route::get('/navbar', [AuthController::class, 'navDashboard']);
    /* CardsPanel de Login */
    Route::get("/cardPanel", [AuthController::class, 'cardPanel']);
});

Route::middleware('role:' . env("ROLPROFE") . ',' . env("ROLALU"))->group(function () {
    /* Modulos para Profesor/Alumno - Puntaje a dar */
    Route::get('/points/getAlumn/{id}', [StudentController::class, 'show']);
    Route::get('/select/actions', [StudentController::class, 'selectActions']);
    Route::post('/points/setPointAction', [StudentController::class, 'store']);
});

/* Modulo Alumno - Funciones : Catalogo de productos (canjear y eliminar) */
Route::middleware('role:' . env("ROLALU"))->group(function () {
    /* Modulo Puntaje Alumnos*/
    Route::get('/students/points/alumns', [StudentController::class, 'index']);
    /* Modulo Catalogo */
    Route::get('/student/catalog/cats', [ProductController::class, 'index']);
    Route::get('/student/catalog/cats/{idCat}', [ProductController::class, 'indexCat']);
    Route::get('/student/catalog-alumn', [ProductController::class, 'indexCatAlum']);
    Route::post('/student/catalog/setPurchase/{id}', [ProductController::class, 'store']);
    Route::delete('/student/catalog/deletePurchase/{id}', [ProductController::class, 'destroy']);
    /* Modulo Notificaciones */
    Route::get('/student/notifications', [NotificationController::class, 'index']);
    Route::post('/student/notifications/response', [NotificationController::class, 'store']);
});

Route::middleware('role:' . env("ROLPROFE"))->group(function () {
    /* Modulo Profesor - Funciones : Alumnos, Catalogo-Alumnos */
    Route::get('/teacher/alumns', [TeacherController::class, 'index']);
    Route::get('/teacher/catalog-college', [ProductController::class, 'indexCatCollege']);
});

Route::middleware('jwt.refresh')->post('logout', [AuthController::class, 'logout']);
