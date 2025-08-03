<?php

namespace App\Http\Controllers\CargoDeclaration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CargoDeclaration\CargoRequest;
use App\Models\Cargo;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(CargoRequest $request)
    {
        $data = $request->validated();

        $cargoData = collect($data)->except(['origin', 'destination'])->toArray();

        $cargo = Cargo::create($cargoData);

        $cargo->cargoInformation()->create(array_merge($data['origin'], ['type' => 'origin']));

        $cargo->cargoInformation()->create(array_merge($data['destination'], ['type' => 'destination']));

        return response()->json(['message' => 'بار با موفقیت ثبت شد', 'data' => $cargo->load('cargoInformation'),]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
