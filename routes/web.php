<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardListController;
use App\Http\Controllers\Admin\TaskCountController;

use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;

require __DIR__.'/auth.php';
require __DIR__.'/AdminAuth.php';
require __DIR__.'/LeadAuth.php';

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/board', [BoardController::class, 'show'])->name('boards.show');
    Route::get('/boards/{board}/{card?}', [BoardController::class, 'show'])->name('boards.show');
    Route::put('/boards/{board}', [BoardController::class, 'update'])->name('boards.update');
    Route::get('/boards', [BoardController::class, 'index'])->name('boards');
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');

    Route::post('/boards/{board}/lists', [CardListController::class, 'store'])->name('cardLists.store');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::put('/cards/{card}/move', [CardController::class, 'move'])->name('cards.move');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::get('cards', [TaskCountController::class, 'index'])->name('cards');

    Route::get('/media/create', function () {
        return Inertia::render('CardListItemModal');
    });

    Route::post('/media', [MediaController::class, 'store'])->name('media.store');

});

Route::middleware('auth:admin')->group(function () {

    Route::get('/admin/dashboard', function () { 
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');

    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
});

Route::middleware('auth:lead')->group(function () {

    // dd(auth()->user);
    Route::get('/lead/dashboard', function(){
        return Inertia::render('Lead/Dashboard');
    })->name('lead.dashboard');

});


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
