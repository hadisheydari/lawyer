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
            'cargoName' => (string) ($this->cargo?->cargoType?->name ?? ''),
            'companyName' => (string) ($this->cargo?->company?->name ?? ''),
            'dateAt' => $this->cargo?->origin?->date_at ? (string) Jalalian::fromTimestamp($this->cargo->origin->date_at)->format('Y/m/d') : '',
            'originCity' => (string) ($this->cargo?->origin?->city?->name ?? ''),
            'destinationCity' => (string) ($this->cargo?->origin?->city?->name ?? ''),
            'fare' => (Int) ($this->fare ?? ''),
            'commission' => (Int) ($this->fare ?? ''),
            'status' => (string) ($this->status ?? ''),
            'partitionWeight' => (Int) ($this->fare ?? ''),
            'packName'=> (string) ($this->cargo?->packing?->name ?? ''),
            'dateTo' => $this->cargo?->destination?->date_to ? (string) Jalalian::fromTimestamp($this->cargo->destination->date_to)->format('Y/m/d') : '',

        ];
    }
}
