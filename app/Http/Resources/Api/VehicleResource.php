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
            'vehicleLicensePlate' => (string) ($this->plate_first?? '' . $this->plate_letter?? '' . $this->plate_second?? ''),
            'vehicleProvincePlate' => (string) ($this->plate_third?? ''),
            'vehiclePlateType' => (string) (__('vehicle_enums.plate_types')?? ''),

            'cargoDestinationCity' => (string) ($this->cargo?->destination?->city?->name ?? ''),
            'partitionFare' => (int) ($this->fare ?? 0),
            'partitionCommission' => (int) ($this->commission ?? 0),
            'partitionStatus' => (string) ($this->status ?? ''),
            'persianCargoStatus' => (string) ( __('cargo_enums.cargo_status.' . $this->status ) ?? ''),
            'partitionWeight' => (int) ($this->weight ?? 0),
            'cargoPackName'=> (string) ($this->cargo?->packing?->name ?? ''),
            'cargoInsuranceCost' => (int) ($this->cargo?->insurance_value ?? 0),
            'cargoDescription' => (string) ($this->cargo?->description ?? ''),
        ];    }
}
