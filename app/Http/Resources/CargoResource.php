<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'type' => $this->type,
            'weight' => $this->weight,
            'number' => $this->number,
            'thickness' => $this->thickness,
            'length' => $this->length,
            'width' => $this->width,
            'insurance' => $this->insurance,
            'fare' => $this->fare,
            'fare_type' => $this->fare_type,
            'cargo_type' => new CargoTypeResource($this->whenLoaded('cargoType')),
            'packing' => new PackingResource($this->whenLoaded('packing')),
            'description' => $this->description,
            'information' => CargoInformationResource::collection($this->whenLoaded('cargoInformation')),
            'reservations' => CargoReservationResource::collection($this->whenLoaded('cargoReservations')),
            'bids' => CargoBidResource::collection($this->whenLoaded('cargoBids')),
            'partitions' => PartitionResource::collection($this->whenLoaded('partitions')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
