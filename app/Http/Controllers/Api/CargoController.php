<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Partition;
use App\Enums\Entity\PropertyType;
use App\Models\Driver;
use App\Http\Resources\Api\CargoResource;


class CargoController extends Controller
{
    public function partitionDetail(Request $request)
    {

        $user = $request->user()->id;

        $driver = Driver::with('vehicle.vehicleDetail')->where('user_id' , $user)->first();
        $companyid = $driver->company?->user_id;
        $vehicleDetailId = $driver->vehicle?->vehicleDetail?->id;
        if (!empty($companyid)){
            $partitions = Partition::where('vehicle_detail_id' , $vehicleDetailId)->whereNull('driver_id')->where('company_id' , $companyid)
                ->where(function ($query) {
                    $query->where('property', '!=', PropertyType::NON_OWNED)
                        ->orWhereNull('property');
                })
                ->get();
        }else{

            $partitions = Partition::where('vehicle_detail_id' , $vehicleDetailId)->whereNull('driver_id')
                ->where(function ($query) {
                    $query->where('property', '!=', PropertyType::OWNED)
                        ->orWhereNull('property');
                })
                ->get();
        }


        return CargoResource::collection($partitions);

    }
}
