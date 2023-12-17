<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaskCountController;
use App\Http\Controllers\Admin\UserCountController;
use App\Http\Controllers\Admin\AdminCountController;
use App\Http\Controllers\Admin\ProjectCountController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/users', UserCountController::class);
Route::get('/admins', AdminCountController::class);
Route::get('/boards', ProjectCountController::class);
Route::get('/cards', TaskCountController::class);