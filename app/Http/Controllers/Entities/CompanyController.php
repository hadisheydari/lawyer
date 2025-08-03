<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Entities\CompanyRequest;
use App\Models\City;
use App\Models\Province;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with(['user', 'drivers'])->where('user_id', auth()->id())->paginate(10);

        $companyIds = $companies->pluck('id');

        $drivers = Driver::with('vehicle')->whereIn('company_id', $companyIds)->paginate(10);

        return view('entities.companies.index', compact('companies', 'drivers'));
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
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('companies', 'public');
        }

        Company::create($data);

        return redirect()->route('dashboard')->with('success', 'شرکت با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load(['user', 'drivers']);

        $province = Province::find($company->province_id);
        $cities = [];

        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');

        return view('entities.companies.show', compact('company', 'provinces' , 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $company->load(['user', 'drivers']);

        $province = Province::find($company->province_id);
        $cities = [];

        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');

        return view('entities.companies.edit', compact('company', 'provinces' , 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->boolean('remove_image')) {
            if ($company->document && Storage::disk('public')->exists($company->document)) {
                Storage::disk('public')->delete($company->document);
            }
            $data['document'] = null;
        }
        if ($request->hasFile('document')) {
            if ($company->document && Storage::disk('public')->exists($company->document)) {
                Storage::disk('public')->delete($company->document);
            }

            $data['document'] = $request->file('document')->store('companies', 'public');
        }

        $company->update($data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'اطلاعات شرکت با موفقیت به‌روزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('dashboard')->with('success', 'شرکت با موفقیت حذف شد.');
    }



    public function driverIndex()
    {
        $companies = Company::with(['user', 'drivers'])->where('user_id', auth()->id())->paginate(10);

        $companyIds = $companies->pluck('id');

        $drivers = Driver::with('vehicle')->whereIn('company_id', $companyIds)->paginate(10);

        return view('driver_management.index', compact('companies', 'drivers'));
    }


}
