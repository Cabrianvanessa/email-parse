<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuccessfulEmailController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Email routes
    Route::post('emails', [SuccessfulEmailController::class, 'store']);
    Route::get('emails/{id}', [SuccessfulEmailController::class, 'show']);
    Route::put('emails/{id}', [SuccessfulEmailController::class, 'update']);
    Route::get('emails', [SuccessfulEmailController::class, 'index']);
    Route::delete('emails/{id}', [SuccessfulEmailController::class, 'destroy']);
});
