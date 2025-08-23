<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;


class CargoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'partitionId' => (Int) ($this->id ?? ''),
            'cargoTypeName' => (string) ($this->cargo?->cargoType?->name ?? ''),
            'cargoCompanyName' => (string) ($this->cargo?->company?->name ?? ''),
            'cargoOriginDateAt' => $this->cargo?->origin?->date_at
                ? (string) Jalalian::forge($this->cargo->origin->date_at)->format('Y/m/d')
                : '',
            'cargoOriginCity' => (string) ($this->cargo?->origin?->city?->name ?? ''),
            'cargoDestinationCity' => (string) ($this->cargo?->destination?->city?->name ?? ''),
            'cargoOriginDescription' => (string) ($this->cargo?->origin?->description?? ''),
            'cargoDestinationDescription' => (string) ($this->cargo?->destination?->description ?? ''),
            'partitionFare' => (int) ($this->fare ?? 0),
            'partitionCommission' => (int) ($this->commission ?? 0),
            'partitionStatus' => (string) ($this->status ?? ''),
            'persianCargoStatus' => (string) ( __('cargo_enums.cargo_status.' . $this->status ) ?? ''),
            'partitionWeight' => (int) ($this->weight ?? 0),
            'cargoPackName'=> (string) ($this->cargo?->packing?->name ?? ''),
            'cargoDestinationDateTo' => $this->cargo?->destination?->date_to
                ? (string) Jalalian::forge($this->cargo->destination->date_to)->format('Y/m/d')
                : '',
            'cargoInsuranceCost' => (int) ($this->cargo?->insurance_value ?? 0),
            'cargoDescription' => (string) ($this->cargo?->description ?? ''),
            'partitionHavaleFile' => (string) ($this->havaleFile ?? ''),
            'partitionBarnamehFile' => (string) ($this->barnamehFile ?? ''),

        ];
    }

}
