<?php

namespace App\Enums\Cargo;

class ReservationStatus
{
    public const PENDING  = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    public const STATUSES = [
        self::PENDING,
        self::ACCEPTED,
        self::REJECTED
    ];
}

