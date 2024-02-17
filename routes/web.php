<?php

use App\Http\Controllers\StatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('tickets')->group(function () {
    Route::get('/open', [TicketController::class, 'open']);
    Route::get('/closed', [TicketController::class, 'closed']);
});

Route::prefix('users')->group(function () {
    Route::get('/{email}/tickets', [UserController::class, 'tickets']);
});

Route::get('/stats', [StatController::class, 'view']);
