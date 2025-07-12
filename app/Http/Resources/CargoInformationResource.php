<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoInformationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cargo_id' => $this->cargo_id,
            'type' => $this->type,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'city_id' => $this->city_id,
            'city' => new CityResource($this->whenLoaded('city')),
            'description' => $this->description,
            'address' => $this->address,
            'date_at' => $this->date_at?->toDateTimeString(),
            'date_to' => $this->date_to?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
