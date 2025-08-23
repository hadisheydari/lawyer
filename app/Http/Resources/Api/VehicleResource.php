<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vehicleId' => (Int) ($this->id ?? ''),
            'vehicleName' => (string) ($this->vehicleDetail?->name ?? ''),
            'vehiclePlateFirst' => (string) ($this->plate_first?? ''),
            'vehiclePlateLetter' => (string) ($this->plate_letter?? ''),
            'vehiclePlateSecond' => (string) ($this->plate_second?? ''),
            'vehicleProvincePlate' => (string) ($this->plate_third?? ''),
            'vehiclePlateType' => (string) (__('vehicle_enums.plate_types.'.$this->plate_type)?? ''),
            'vehicleCostCenter' => (string) ($this->cost_center?? ''),
            'vehicleSmartNumber' => (string) ($this->smart_number?? ''),

        ];
    }
}
