<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOtpResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'code'      => $this->code,
            'expires_at'=> $this->expires_at?->toDateTimeString(),
            'is_expired'=> $this->isExpired(),
            'remaining_seconds' => $this->remaining_seconds,
        ];
    }
}
