<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Company;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Entities\DriverRequest;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::with(['user', 'company'])->where('user_id', auth()->id())->get();

        return view('entities.drivers.index', compact( 'drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DriverRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $fileFields = [
            'national_card_file' => 'drivers/national_cards',
            'smart_card_file' => 'drivers/smart_cards',
            'certificate_file' => 'drivers/certificates',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store($path , 'public');
            }
        }

        Driver::create($data);

        return redirect()->route('dashboard')
            ->with('success', 'راننده با موفقیت ساخته شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $companies = Company::with('user')->get()->mapWithKeys(function ($company) {
            return [$company->id => $company->user->name];
        });
        $driver->load(['user', 'company', 'city', 'province', 'vehicle']);
        $province = Province::find($driver->province_id);
        $cities = [];
        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');
        return view('entities.drivers.show', compact('driver' , 'companies' , 'provinces' , 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        $companies = Company::with('user')->get()->mapWithKeys(function ($company) {
            return [$company->id => $company->user->name];
        });
        $driver->load(['user', 'company', 'city', 'province', 'vehicle']);
        $province = Province::find($driver->province_id);
        $cities = [];
        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');
        return view('entities.drivers.edit', compact('driver' , 'companies' , 'provinces' , 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest $request, Driver $driver)
    {
        $data = $request->validated();
        $fileFields = [
            'national_card_file' => 'drivers/national_cards',
            'smart_card_file' => 'drivers/smart_cards',
            'certificate_file' => 'drivers/certificates',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->boolean("remove_{$field}")) {
                if ($driver->$field && Storage::disk('public')->exists($driver->$field)) {
                    Storage::disk('public')->delete($driver->$field);
                }
                $data[$field] = null;
            }

            if ($request->hasFile($field)) {
                if ($driver->$field && Storage::disk('public')->exists($driver->$field)) {
                    Storage::disk('public')->delete($driver->$field);
                }
                $data[$field] = $request->file($field)->store($path, 'public');
            }
        }

        $driver->update($data);

        return redirect($request->input('redirect_to', route('drivers.index')))
            ->with('success', 'اطلاعات راننده با موفقیت به‌روزرسانی شد.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        //
    }
}
