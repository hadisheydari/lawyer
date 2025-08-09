<?php

namespace App\Http\Controllers\CargoDeclaration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CargoReservation;
use App\Models\Cargo;
use App\Http\Requests\CargoDeclaration\CargoReservationRequest;
use App\Models\Company;
class CargoReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($cargo = null)
    {
        $cargos = CargoReservation::with(['cargo', 'company'])->filterByCargo($cargo)->paginate(10);

        return view('cargo_declaration.cargos.index', compact('cargos', 'cargo'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Cargo $cargo)
    {
        $cargo->load(['cargoType' , 'packing' , 'origin.province' , 'destination.province']);
        $companies = Company::where('company_type' , 'large_scale')->orWhere('province_id' , $cargo->origin->province_id)->get()->mapWithKeys(function ($company) {
            return [$company->user->id => $company->user->name];
        });
        return view('cargo_declaration.cargo_reservations.create', compact( 'cargo' , 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoReservationRequest $request)
    {
        $cargoId = $request->cargo_id;
        $companyIds = (array) $request->company_id;

        $existing = CargoReservation::where('cargo_id', $cargoId)->whereIn('company_id', $companyIds)->pluck('company_id')->all();

        $toInsert = array_diff($companyIds, $existing);

        if (!$toInsert) {
            return back()->withErrors(['company_id' => 'تمام شرکت‌ها قبلا رزرو شده‌اند.']);
        }

        foreach ($toInsert as $companyId) {
            CargoReservation::create([
                'cargo_id' => $cargoId,
                'company_id' => $companyId,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('cargo_reservations.index', $cargoId)->with('success', 'رزروها ثبت شد.');
    }


    /**
     * Display the specified resource.
     */
    public function show(CargoReservation $cargo_reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargoReservation $cargo_reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoReservationRequest $request, CargoReservation $cargo_reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CargoReservation $cargo_reservation)
    {
        //
    }
}
