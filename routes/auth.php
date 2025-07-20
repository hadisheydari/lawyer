<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 🔐 Web Authentication Routes
Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', fn () => view('auth.login'))->name('login');
        Route::get('/register', fn () => view('auth.register'))->name('register');
        Route::get('/verify', fn () => view('auth.identity-verification'))->name('verify');

        Route::post('/login', [AuthController::class, 'login'])->name('loginAction');
        Route::post('/register', [AuthController::class, 'register'])->name('registerAction');
        Route::post('/verify', [AuthController::class, 'verify'])->name('verifyAction');

    });
    Route::get('/select-role', fn () => view('auth.select-role'))->name('selectRole');
    Route::get('/select-role/{role}/{type?}', [AuthController::class, 'selectRole'])->name('selectRoleAction');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logoutAction');
});

// 🔐 API Authentication Routes (using Sanctum)
Route::prefix('api')->middleware('api')->group(function () {
    Route::middleware('guest:sanctum')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/verify', [AuthController::class, 'verify']);

    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});
