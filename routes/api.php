<?php

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

Route::resource('categories', 'App\Http\Controllers\Api\CategoryController');
Route::resource('colleges', 'App\Http\Controllers\Api\CollegeController');
Route::resource('notifications', 'App\Http\Controllers\Api\NotificationController');
Route::resource('actions', 'App\Http\Controllers\Api\ActionController');
