<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Rating\RatingController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/freePartitions', [CargoController::class, 'freePartitions']);
    Route::Put('/partitions/acceptByDriver/{partition}', [CargoController::class, 'acceptByDriver']);
    Route::get('/driverPartitions', [CargoController::class, 'driverPartitions']);
    Route::Put('/partitions/confirmation/{partition}', [CargoController::class, 'confirmationDriver']);
    Route::Put('/partitions/evacuated/{partition}/{cade}', [CargoController::class, 'evacuatedDriver']);
    Route::post('/rating',  [RatingController::class, 'store']);
    Route::get('/driverVehicle', [CargoController::class, 'driverVehicle']);



});





