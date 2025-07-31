<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\VehicleDetailRequest;
use App\Models\VehicleDetail;

class VehicleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleDetails = VehicleDetail::latest()->paginate(10);
        return view('setting.vehicle_details.index', compact('vehicleDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting.vehicle_details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleDetailRequest $request)
    {
        VehicleDetail::create($request->validated());

        return redirect()->route('vehicle-details.index')
            ->with('success', 'اطلاعات نوع بارگیر با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleDetail $vehicle_detail)
    {
        return view('setting.vehicle_details.show', compact('vehicle_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleDetail $vehicle_detail)
    {
        return view('setting.vehicle_details.edit', compact('vehicle_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleDetailRequest $request, VehicleDetail $vehicle_detail)
    {
        $vehicle_detail->update($request->validated());

        return redirect()->route('vehicle-details.index')
            ->with('success', 'اطلاعات نوع بارگیر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleDetail $vehicle_detail)
    {
        $vehicle_detail->delete();

        return redirect()->route('vehicle-details.index')
            ->with('success', 'اطلاعات نوع بارگیر با موفقیت حذف شد.');
    }
}
