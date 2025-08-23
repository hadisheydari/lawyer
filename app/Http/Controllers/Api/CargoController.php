<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Http\Resources\UserOtpResource;
use App\Models\Vehicle;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Partition;
use App\Enums\Entity\PropertyType;
use App\Models\Driver;
use App\Http\Resources\Api\CargoResource;
use App\Http\Resources\Api\VehicleResource;
use App\Enums\Cargo\CargoStatus;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;

class CargoController extends Controller
{
    use ApiResponseTrait;

    public function freePartitions(Request $request)
    {
        $userId = $request->user()->id;

        $driver = Driver::with('vehicle.vehicleDetail')->where('user_id', $userId)->first();

        $companyId = $driver?->company?->user_id;
        $vehicleDetailId = $driver?->vehicle?->vehicleDetail?->id;

        if (!$vehicleDetailId) {
            return CargoResource::collection([]);
        }

        $partitions = Partition::query()->where('vehicle_detail_id', $vehicleDetailId)->whereNull('driver_id')
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId)->where(function ($q) {
                    $q->where('property', '!=', PropertyType::NON_OWNED)->orWhereNull('property');
                });
            }, function ($query) {
                $query->where(function ($q) {
                    $q->where('property', '!=', PropertyType::OWNED)->orWhereNull('property');
                });
            })
            ->get();
        return $this->success('با موفقیت انجام شد .', 200, CargoResource::collection($partitions));

    }

    public function acceptByDriver(Request $request, Partition $partition): JsonResponse
    {
        $userId = $request->user()->id;

        $partition->status = CargoStatus::RESERVED;
        $partition->driver_id = $userId;
        $partition->save();

        return $this->success( 'پارتیشن با موفقیت رزرو شد.', 200, null);

    }

    public function driverPartitions(Request $request)
    {
        $userId = $request->user()->id;
        $partitions = Partition::query()->where('driver_id', $userId)->get();
        return $this->success('با موفقیت انجام شد .', 200,  CargoResource::collection($partitions));


    }

    public function confirmationDriver(Partition $partition): JsonResponse
    {
        try {
            $otp = app(OtpService::class)->deliverCode($partition);
            return $this->success(
                'کد برای شما ارسال شد.',
                201,
                [
                    'otp' => new UserOtpResource($otp),
                ]
            );
        } catch (\Throwable $e) {
            return $this->success('کد با موفقیت ارسال شد .', 200, null);


        }
    }


    public function evacuatedDriver(OtpService $otpService, Partition $partition, string $code)
    {
        if ($otpService->verifyPartition($partition, $code)) {
            $partition->status = CargoStatus::DELIVERED;
            $partition->save();
            return $this->success('پارتیشن با موفقیت تحویل داده شد.', 200, null);

        }
    }
    public function driverVehicle(Request $request)
    {
        $driver = $request->user()->driver;

        if (! $driver) {
            return $this->error('راننده‌ای برای این کاربر یافت نشد.', 404);
        }

        $vehicle = $driver->vehicle()->first();

        if (! $vehicle) {
            return $this->error('وسیله نقلیه‌ای یافت نشد.', 404);
        }

        return $this->success(
            'با موفقیت انجام شد.',
            200,
            new VehicleResource($vehicle)
        );
    }



}
