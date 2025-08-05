<?php

namespace App\Http\Controllers\CargoDeclaration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CargoDeclaration\CargoRequest;
use App\Models\Cargo;
use App\Models\Packing;
use App\Models\CargoType;
use App\Models\Insurance;
use App\Models\Province;
use App\Models\City;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cargos = Cargo::with(['cargoType', 'owner'])->where('owner_id', auth()->id())->orWhere('assigned_company_id', auth()->id())->paginate(10);

        return view('cargo_declaration.cargos.index', compact( 'cargos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cargoTypes = CargoType::pluck('name', 'id');
        $packings = Packing::pluck('name', 'id');
        $insurances = Insurance::select('id', 'name', 'coefficient')->get();
        $provinces = Province::pluck('name', 'id');

        return view('cargo_declaration.cargos.create', compact('cargoTypes', 'packings', 'insurances' , 'provinces'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = auth()->id();

        $cargoData = collect($data)->except(['origin', 'destination'])->toArray();

        $cargo = Cargo::create($cargoData);

        $cargo->origin()->create(array_merge($data['origin'], ['type' => 'origin']));

        $cargo->destination()->create(array_merge($data['destination'], ['type' => 'destination']));

        return redirect()->route('cargos.index')->with('success', ' بار با موفقیت ثبت شد.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Cargo $cargo)
    {
        $cargo->load(['origin.city', 'origin.province', 'destination.city', 'destination.province', 'cargoType', 'packing', 'insurance']);

        $cargoTypes = CargoType::pluck('name', 'id');
        $packings = Packing::pluck('name', 'id');
        $insurances = Insurance::select('id', 'name', 'coefficient')->get();
        $provinces = Province::pluck('name', 'id');

        $cities = City::where('province_code', optional($cargo->origin->province)->code)->pluck('name', 'id');
        $cities1 = City::where('province_code', optional($cargo->destination->province)->code)->pluck('name', 'id');

        return view('cargo_declaration.cargos.show', compact('cargo', 'cargoTypes', 'packings', 'insurances', 'provinces', 'cities', 'cities1'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cargo $cargo)
    {
        $cargo->load([
            'origin.city', 'origin.province',
            'destination.city', 'destination.province',
            'cargoType', 'packing', 'insurance'
        ]);

        $cargoTypes = CargoType::pluck('name', 'id');
        $packings = Packing::pluck('name', 'id');
        $insurances = Insurance::select('id', 'name', 'coefficient')->get();
        $provinces = Province::pluck('name', 'id');

        $cities = City::where('province_code', optional($cargo->origin->province)->code)->pluck('name', 'id');
        $cities1 = City::where('province_code', optional($cargo->destination->province)->code)->pluck('name', 'id');

        return view('cargo_declaration.cargos.edit', compact(
            'cargo', 'cargoTypes', 'packings', 'insurances', 'provinces', 'cities', 'cities1'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoRequest $request, Cargo $cargo)
    {
        $data = $request->validated();

        $cargoData = collect($data)->except(['origin', 'destination'])->toArray();

        $cargo->update($cargoData);

        $cargo->origin->update($data['origin']);

        $cargo->destination->update($data['destination']);

        return redirect()->route('cargos.index')->with('success', 'بار با موفقیت ویرایش شد.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cargo $cargo)
    {

        $cargo->origin()->delete();
        $cargo->destination()->delete();
        $cargo->delete();

        return redirect()->route('cargos.index')->with('success', 'بار با موفقیت حذف شد.');
    }

    public function setType(Cargo $cargo , $type)
    {
        $cargo->update('');
    }
}
