<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\InsuranceRequest;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurances = Insurance::latest()->paginate(10);
        return view('setting.insurances.index', compact('insurances'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting.insurances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InsuranceRequest $request)
    {
        Insurance::create($request->validated());

        return redirect()->route('insurances.index')
            ->with('success', 'بیمه با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        return view('setting.insurances.show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        return view('setting.insurances.edit', compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InsuranceRequest $request, Insurance $insurance)
    {
        $insurance->update($request->validated());

        return redirect()->route('insurances.index')
            ->with('success', 'بیمه با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        return redirect()->route('insurances.index')
            ->with('success', 'بیمه با موفقیت حذف شد.');
    }
}
