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
        if ($cargo && is_string($cargo)) {
            $cargos = Cargo::where('type', $cargo)->where('owner_id', auth()->id())->orWhere('assigned_company_id', auth()->id())
                ->orWhere(function ($query) use ($cargo) {
                    $query->whereHas($cargo, function ($q) {
                        $q->where('company_id', auth()->id());
                    });
                })->with([$cargo, $cargo . '.company',  'origin.province' , 'destination.province'])->paginate(10);
        } else {
            $cargos = Cargo::paginate(10);
        }

        return view('cargo_declaration.cargo_reservations.index', compact('cargos' ,'cargo' ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Cargo $cargo)
    {
        $cargo->load(['cargoType' , 'packing' , 'origin.province' , 'destination.province']);
        $companies = Company::where('company_type', 'large_scale')->orWhere('province_id', $cargo->origin->province_id)->get()->mapWithKeys(function ($company) {
            return [$company->user->id => $company->user->name];})->toArray();

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

        return redirect()->route('cargo_reservations.index', $request->redirect_to)->with('success', 'رزروها ثبت شد.');
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
    public function edit(Cargo $cargo_reservation)
    {
        $cargo_reservation->load(['cargoType' , 'rfq.company' , 'reserve.company',  'origin.province' , 'destination.province']);
        $companies = Company::where('company_type', 'large_scale')->orWhere('province_id', $cargo_reservation->origin->province_id)->get()->mapWithKeys(function ($company) {
            return [$company->user->id => $company->user->name];})->toArray();
        $company = null;
        if ($cargo_reservation->type === 'rfq'){
            $company = $cargo_reservation->rfq->pluck('company.id')->unique()->toArray();
        }elseif ($cargo_reservation->type === 'reserve'){
            $company = $cargo_reservation->reserve->company->id;
        }
        return view('cargo_declaration.cargo_reservations.edit', compact( 'cargo_reservation' , 'companies' , 'company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoReservationRequest $request, Cargo $cargo_reservation)
    {
        $type = $cargo_reservation->type;

        $cargo_reservation->$type()->delete();

        $cargoId = $request->cargo_id;
        $companyIds = (array) $request->company_id;

        foreach ($companyIds as $companyId) {
            CargoReservation::create([
                'cargo_id' => $cargoId,
                'company_id' => $companyId,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('cargo_reservations.index', $request->redirect_to)->with('success', 'رزروها ویرایش شد  .');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cargo $cargo_reservation)
    {

        $type = $cargo_reservation->type;
        $cargo_reservation->$type()->delete();
        $cargo_reservation->type = null;
        $cargo_reservation->save();
        return redirect()->route('cargo_reservations.index', $type )->with('success', 'رزروها حذف  شد.');

    }
}
