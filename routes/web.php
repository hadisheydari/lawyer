<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Entities\CompanyController;
use App\Http\Controllers\Entities\DriverController;
use App\Http\Controllers\Entities\ProductOwnerController;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Setting\PackingController;
use App\Http\Controllers\Setting\VehicleDetailController;
use App\Http\Controllers\Setting\InsuranceController;
use App\Http\Controllers\Setting\CargoTypeController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\CargoDeclaration\CargoController;
use App\Http\Controllers\CargoDeclaration\CargoReservationController;


require __DIR__.'/auth.php';

Route::middleware(['auth', 'role.selected'])->group(function () {
    Route::get('/', fn() => view('dashboard'))->name('dashboard');
    Route::resource('companies', CompanyController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('product-owners', ProductOwnerController::class);
    Route::resource('packings', PackingController::class);
    Route::resource('vehicle_details', VehicleDetailController::class);
    Route::resource('insurances', InsuranceController::class);
    Route::resource('cargo_types', CargoTypeController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::get('driver_management/index', [CompanyController::class ,  'driverIndex'])->name('driverManagement');
    Route::get('allocation/{driver}', [DriverController::class , 'allocation'])->name('allocation');
    Route::get('vehicles/detachDriver/{vehicle}', [VehicleController::class , 'detachDriver'])->name('vehicles.detachDriver');
    Route::resource('cargos', CargoController::class);
    Route::get('/cargos/{cargo}/type/{type}', [CargoController::class, 'setType'])->name('cargos.type');
    Route::get('/cargo_reservations/create/{cargo}', [CargoReservationController::class, 'create'])->name('cargo_reservations.create');
    Route::get('/cargo_reservations/index/{cargo}', [CargoReservationController::class, 'index'])->name('cargo_reservations.index');
    Route::resource('cargo_reservations', CargoReservationController::class)->except('create' , 'index');


});

Route::get('/get-cities/{province}', [CityController::class, 'getCities'])->name('getCities');
Route::get('/get-city-scale/{city}', [CityController::class, 'getCityScale'])->name('getCityScale');


