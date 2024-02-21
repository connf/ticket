<?php

use App\Http\Controllers\StatController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('tickets')->group(function () {
    Route::post('/open', [TicketController::class, 'open']);
    Route::post('/closed', [TicketController::class, 'closed']);
    Route::post('/view/{id}', [TicketController::class, 'view']);
});

Route::prefix('users')->group(function () {
    Route::post('/{email}/tickets', [UserController::class, 'tickets']);
});

Route::post('/stats', [StatController::class, 'view']);
