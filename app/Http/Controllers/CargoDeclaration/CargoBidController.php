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
    public function create(Cargo $cargo)
    {
        $cargo->load(['cargoType' , 'origin.province' , 'destination.province']);
        return view('cargo_declaration.cargo_bid.create', compact('cargo'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoBidRequest $request)
    {
        $data = $request->validated() + ['company_id' => auth()->id()];

        if (CargoBid::where('company_id', $data['company_id'])->where('cargo_id', $data['cargo_id'])->exists()) {
            return redirect()->route('cargo_bids.list_of_bids' , $data['cargo_id'])->with('error', 'پیشنهاد شما قبلا برای این بار ثبت شده');
        }

        CargoBid::create($data);

        return redirect()->route('cargo_bids.list_of_bids' , $data['cargo_id'])->with('success', 'پیشنهاد با موفقیت ثبت شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoBid $cargo_bid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargoBid $cargo_bid)
    {
        return view('cargo_declaration.cargo_bid.edit', compact('cargo_bid'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoBidRequest $request, CargoBid $cargo_bid)
    {
        $cargo_bid->update($request->validated());

        return redirect()->route('cargo_bids.list_of_bids' ,$cargo_bid->cargo_id)->with('success', 'پیشنهاد با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CargoBid $cargo_bid)
    {
        $cargo_bid->delete();

        return back()->with('success', 'پیشنهاد با موفقیت حذف شد.');
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

        return redirect()->route('cargo_bids.list_of_bids' , $cargo->id)->with('success', 'عملیات با موفقیت انجام شد.');
    }

    public function list_of_bids( Cargo $cargo)
    {
        $cargo_bids = CargoBid::where('cargo_id', $cargo->id)->whereHas('cargo', function ($q) {
                $q->whereNull('assigned_company_id')->where('date_to', '>=', now());
            })->with('company')->get();

        $cargo = $cargo->id;
        return view('cargo_declaration.cargo_bid.list_of_bids', compact('cargo_bids' , 'cargo'));
    }

    public function confirmCargo(CargoBid $cargo_bid, string $status)
    {

        $cargo_bid->update(['status' => $status]);

        if ($status === 'accepted') {
            $cargo_bid->cargo()->update(['assigned_company_id' => $cargo_bid->company_id]);

            return redirect()->route('cargo_bids.index')->with('success', 'عملیات با موفقیت انجام شد.');

        }

        return back()->with('success', 'عملیات با موفقیت انجام شد.');
    }


}
