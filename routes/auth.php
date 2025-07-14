<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 🔐 Web Authentication Routes
Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', fn () => view('auth.login'))->name('login');
        Route::get('/register', fn () => view('auth.register'))->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('loginAction');;
        Route::post('/register', [AuthController::class, 'register'])->name('registerAction');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

// 🔐 API Authentication Routes (using Sanctum)
Route::prefix('api')->middleware('api')->group(function () {
    Route::middleware('guest:sanctum')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});
