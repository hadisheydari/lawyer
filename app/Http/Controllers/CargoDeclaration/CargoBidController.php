<?php

namespace App\Http\Controllers\CargoDeclaration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\CargoBid;
use App\Http\Requests\CargoDeclaration\CargoBidRequest;

class CargoBidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cargos = Cargo::where('type', 'free')
            ->when(!auth()->user()->hasRole('company'), function ($q) {
                $q->where('owner_id', auth()->id());
            })->get();

        return view('cargo_declaration.cargo_bid.index', compact('cargos'));
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
    public function store(Request $request)
    {
        //
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

    public function bid(Cargo $cargo)
    {
        return view('cargo_declaration.cargo_bid.set_bid', compact('cargo'));
    }


    public function set_bid(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'date_at' => gregorian_datetime_rules(false),
            'date_to' => gregorian_datetime_rules(false),
        ]);

        $cargo->update($validated);

        return back()->with('success', 'عملیات با موفقیت انجام شد.');
    }

    public function list_of_bids( Cargo $cargo)
    {
        $cargo_bids = CargoBid::where('cargo_id', $cargo->id)->whereHas('cargo', function ($q) {
                $q->whereNull('assigned_company_id')->where('date_to', '>=', now());
            })->with('company')->get();

        $cargo = $cargo->id;
        return view('cargo_declaration.cargo_bid.list_of_bids', compact('cargo_bids' , 'cargo'));
    }

}
