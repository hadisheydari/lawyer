<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'cargo_id'    => $this->cargo_id,
            'company_id'  => $this->company_id,
            'status'      => $this->status,

            // روابط
            'company'     => new UserResource($this->whenLoaded('company')),
            'cargo'       => new CargoResource($this->whenLoaded('cargo')),

            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
