<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\VehicleDetail;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Http\Requests\Vehicle\VehicleRequest;
class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::with('vehicleDetail')->latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        return view('vehicles.create', compact('vehicleDetails'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleRequest $request)
    {
        Vehicle::create($request->validated());
        return redirect()->route('vehicles.index')->with('success', 'ماشین با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {

        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        return view('vehicles.show', compact('vehicleDetails' , 'vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $vehicle->load('vehicleDetail');
        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        return view('vehicles.edit', compact('vehicleDetails' , 'vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return redirect($request->input('redirect_to', route('vehicles.index')))
            ->with('success', 'مکانیزم با موفقیت به‌روزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'مکانیزم با موفقیت حذف شد.');

    }


    public function detachDriver(Vehicle $vehicle)
    {
        $vehicle->driver_id = null;
        $vehicle->save();

        return redirect()->back()->with('success', 'راننده از وسیله نقلیه جدا شد.');
    }


}
