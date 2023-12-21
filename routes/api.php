<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaskCountController;
use App\Http\Controllers\Admin\UserCountController;
use App\Http\Controllers\Admin\AdminCountController;
use App\Http\Controllers\Admin\ProjectCountController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Lead\LeadController;


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

Route::get('/getUsers', [ReportsController::class, 'users']);
Route::get('/getBoards', [ReportsController::class, 'boards']);
Route::get('/getCards', [ReportsController::class, 'cards']);


Route::get('/currentUser', [UserController::class, 'currentUser']); 

Route::get('/users', UserCountController::class);
Route::get('/admins', AdminCountController::class);
Route::get('/boards', ProjectCountController::class);
Route::get('/cards', TaskCountController::class); 

Route::get('/boards/{id}', [LeadController::class, 'getBoardByUser']);
Route::get('/card-lists/{board_id}/{user_id}', [LeadController::class, 'getCardListByBoard']);
Route::get('/card/{board_id}/{user_id}/{card_list_id}', [LeadController::class, 'getCardByCardList']);

Route::post('/users', [LeadController::class, 'createMember']);
Route::post('/boards', [LeadController::class, 'createBoard']);
 