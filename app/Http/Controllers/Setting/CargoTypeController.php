<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\CargoTypeRequest;
use App\Models\CargoType;

class CargoTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cargoTypes = CargoType::latest()->paginate(10);
        return view('setting.cargo-types.index', compact('cargoTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting.cargo-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoTypeRequest $request)
    {
        CargoType::create($request->validated());
        return redirect()->route('cargo_types.index')->with('success', 'نوع بار با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoType $cargo_type)
    {
        return view('setting.cargo-types.show', compact('cargo_type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargoType $cargo_type)
    {
        return view('setting.cargo-types.edit', compact('cargo_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoTypeRequest $request, CargoType $cargo_type)
    {
        $cargo_type->update($request->validated());
        return redirect()->route('cargo_types.index')->with('success', 'نوع بار با موفقیت به‌روزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CargoType $cargo_type)
    {
        $cargo_type->delete();
        return redirect()->route('cargo_types.index')->with('success', 'نوع بار با موفقیت حذف شد.');
    }
}
