<?php

namespace App\Http\Controllers\CargoDelivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partition;
use App\Models\Cargo;
use App\Models\VehicleDetail;
use App\Http\Requests\CargoDelivery\PartitionRequest;
use Illuminate\Support\Facades\Storage;

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
        $vehicleDetails = VehicleDetail::whereHas('vehicles')->pluck('name', 'id');
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
            ->with('success', 'پارتیشن با موفقیت ساخته شد.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Partition $partition)
    {
        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        $partition->load(['cargo.cargoType']);
        $status = null;
        return view('cargo_delivery.partition.show', compact('partition' , 'status','vehicleDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit(Partition $partition , ?string $status = null)
    {
        $vehicleDetails = VehicleDetail::pluck('name', 'id');
        $partition->load(['cargo.cargoType']);

        return view('cargo_delivery.partition.edit', compact('partition', 'status' , 'vehicleDetails'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(PartitionRequest $request, Partition $partition)
    {

        $data = $request->validated();
        $fileFields = [
            'havaleFile' => 'havale',
            'barnamehFile' => 'barnameh',
        ];

        foreach ($fileFields as $field => $status) {
            if ($request->boolean("remove_{$field}")) {
                if ($partition->$field && Storage::disk('public')->exists($partition->$field)) {
                    Storage::disk('public')->delete($partition->$field);
                }
                $data[$field] = null;
            }

            if ($request->hasFile($field)) {
                if ($partition->$field && Storage::disk('public')->exists($partition->$field)) {
                    Storage::disk('public')->delete($partition->$field);
                }
                $data[$field] = $request->file($field)->store('partition/'.$field , 'public');
                if ($partition->status !== 'barnameh'){
                    $data['status'] = $status;
                }
            }
        }
        $partition->update($data);

        return redirect()->route('partitions.index_of_partition' ,['cargo' => $partition->cargo_id, 'status' => $partition->status])
            ->with('success', 'عملیات با موفقیت انجام شد.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partition $partition)
    {
        $partition->delete();
        return back()->with('success', 'پارتیشن با موفقیت حذف شد.');
    }
    public function index_of_partition(Cargo $cargo , string $status)
    {
        $partitions = $cargo->partitions()->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->with('cargo.cargoType')->get();
        $cargo = $cargo->id;

        return view('cargo_delivery.partition.index_of_partition', compact('partitions', 'status' , 'cargo'));

    }
    public function driver(Partition $partition , string $property )
    {
        $partition->property = $property;
        $partition->save();
        return back();
    }
}
