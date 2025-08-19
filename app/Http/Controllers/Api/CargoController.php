<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Http\Resources\UserOtpResource;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Partition;
use App\Enums\Entity\PropertyType;
use App\Models\Driver;
use App\Http\Resources\Api\CargoResource;
use App\Enums\Cargo\CargoStatus;
use Illuminate\Http\JsonResponse;

class CargoController extends Controller
{
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

        return response()->json([
            'success' => true,
            'message' => 'با موفقیت انجام شد .',
            'data'=> CargoResource::collection($partitions)
        ], 200);
    }

    public function acceptByDriver(Request $request, Partition $partition): JsonResponse
    {
        $userId = $request->user()->id;

        $partition->status = CargoStatus::RESERVED;
        $partition->driver_id = $userId;
        $partition->save();

        return response()->json([
            'success' => true,
            'message' => 'پارتیشن با موفقیت رزرو شد.',
        ], 200);
    }

    public function driverPartitions(Request $request)
    {
        $userId = $request->user()->id;
        $partitions = Partition::query()->where('driver_id', $userId)->get();
        return response()->json([
            'success' => true,
            'message' => 'با موفقیت انجام شد .',
            'data'=> CargoResource::collection($partitions)
        ], 200);

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

            return response()->json([
                'success' => true,
                'message' => 'کد با موفقیت ارسال شد .',
            ], 200);        }
    }


    public function evacuatedDriver(OtpService $otpService, Partition $partition, string $code)
    {
        if ($otpService->verifyPartition($partition, $code)) {
            $partition->status = CargoStatus::DELIVERED;
            $partition->save();

            return response()->json([
                'success' => true,
                'message' => 'پارتیشن با موفقیت تحویل داده شد.',
            ], 200);
        }
    }

}
