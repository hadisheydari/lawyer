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
use App\Http\Controllers\CargoDeclaration\CargoBidController;
use App\Http\Controllers\CargoDelivery\PartitionController;
use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\Complaint\ComplaintController;


require __DIR__.'/auth.php';

Route::middleware(['auth', 'role.selected'])->group(function () {
    Route::get('/', fn() => view('dashboard'))->name('dashboard');
    //نقش ها
    Route::resource('companies', CompanyController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('product-owners', ProductOwnerController::class);
    //
    //تنظیمات
    Route::resource('packings', PackingController::class);
    Route::resource('vehicle_details', VehicleDetailController::class);
    Route::resource('insurances', InsuranceController::class);
    Route::resource('cargo_types', CargoTypeController::class);
    //
    //مکانیزم
    Route::resource('vehicles', VehicleController::class);
    //
    //مدیریت رانندها
    Route::get('driver_management/index', [CompanyController::class ,  'driverIndex'])->name('driverManagement');
    Route::get('allocation/{driver}', [DriverController::class , 'allocation'])->name('allocation');
    Route::get('vehicles/detachDriver/{vehicle}', [VehicleController::class , 'detachDriver'])->name('vehicles.detachDriver');
    //
    //بار
    Route::resource('cargos', CargoController::class);
    //
    // نوع بار
    Route::get('/cargos/{cargo}/type/{type}', [CargoController::class, 'setType'])->name('cargos.type');
    Route::get('/cargo_reservations/create/{cargo}', [CargoReservationController::class, 'create'])->name('cargo_reservations.create');
    Route::get('/cargo_reservations/index/{cargo}', [CargoReservationController::class, 'index'])->name('cargo_reservations.index');
    Route::get('/cargo_reservations/confirmCargo/{cargo}/{status}', [CargoReservationController::class, 'confirmCargo'])->name('cargo_reservations.confirmCargo');
    Route::resource('cargo_reservations', CargoReservationController::class)->except('create' , 'index');

    Route::get('/cargo_bids/bid/{cargo}', [CargoBidController::class, 'bid'])->name('cargo_bids.bid');
    Route::put('/cargo_bids/set_bid/{cargo}', [CargoBidController::class, 'set_bid'])->name('cargo_bids.set_bid');
    Route::get('/cargo_bids/list_of_bids/{cargo}', [CargoBidController::class, 'list_of_bids'])->name('cargo_bids.list_of_bids');
    Route::get('/cargo_bids/create/{cargo}', [CargoBidController::class, 'create'])->name('cargo_bids.create');
    Route::get('/cargo_bids/confirmCargo/{cargo_bid}/{status}', [CargoBidController::class, 'confirmCargo'])->name('cargo_bids.confirmCargo');
    Route::resource('cargo_bids', CargoBidController::class)->except('create');
   //
    //پارتیشن
    Route::get('/partitions/index/{status?}', [PartitionController::class, 'index'])->name('partitions.index');
    Route::get('/partitions/create/{cargo}', [PartitionController::class, 'create'])->name('partitions.create');
    Route::get('/partitions/index_of_partition/{cargo}/{status}', [PartitionController::class, 'index_of_partition'])->name('partitions.index_of_partition');
    Route::get('/partitions/{partition}/edit/{status?}', [PartitionController::class, 'edit'])->name('partitions.edit');
    Route::get('/partitions/driver/{partition}/{property}', [PartitionController::class, 'driver'])->name('partitions.driver');
    Route::resource('partitions', PartitionController::class)->except('index' , 'create' , 'edit');

    //
    //امتیازدهی

    Route::resource('ratings', RatingController::class)->except('create');
    Route::get('/ratings/create/{partition}', [RatingController::class, 'create'])->name('ratings.create');
   //
    //شکایت
    Route::post('/complaints/review/{cargo}', [ComplaintController::class, 'store_review'])->name('complaints.review');
    Route::resource('complaints', ComplaintController::class)->except('create');
    //

});

Route::get('/get-cities/{province}', [CityController::class, 'getCities'])->name('getCities');
Route::get('/get-city-scale/{city}', [CityController::class, 'getCityScale'])->name('getCityScale');


