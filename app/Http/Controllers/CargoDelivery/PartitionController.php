<?php

namespace App\Http\Controllers\CargoDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partition;
use App\Models\Cargo;
use App\Models\VehicleDetail;
use App\Http\Requests\CargoDelivery\PartitionRequest;

class PartitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?string $status = null)
    {
        $cargos = Cargo::where('owner_id', auth()->id())->orWhere('assigned_company_id', auth()->id())
            ->with(['owner', 'company', 'origin.province', 'destination.province', 'cargoType'])->get();

        return view('cargo_delivery.partition.index', compact('cargos', 'status'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Cargo $cargo)
    {
        $partition = new Partition();
        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        return view('cargo_delivery.partition.create', compact('cargo', 'partition' , 'vehicleDetails'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PartitionRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = auth()->id();
        Partition::create($data);
        return redirect()->route('partitions.index_of_partition' ,['cargo' => $data['cargo_id'], 'status' => 'free'])
            ->with('success', 'راننده با موفقیت ساخته شد.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Partition $partition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit(Partition $partition , ?string $status)
    {
        return view('cargo_delivery.partition.edit', compact('partition', 'status'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(PartitionRequest $request, Partition $partition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partition $partition)
    {
        //
    }
    public function index_of_partition(Cargo $cargo , string $status)
    {
        $partitions = $cargo->partitions()->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->with('cargo.cargoType')->get();
        $cargo = $cargo->id;

        return view('cargo_delivery.partition.index_of_partition', compact('partitions', 'status' , 'cargo'));

    }
}
