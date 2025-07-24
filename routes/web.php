<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Entities\CompanyController;
use App\Http\Controllers\Entities\DriverController;
use App\Http\Controllers\Entities\ProductOwnerController;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role.selected'])->group(function () {
    Route::get('/', fn() => view('dashboard'))->name('dashboard');
    Route::resource('companies', CompanyController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('product-owners', ProductOwnerController::class);

});
