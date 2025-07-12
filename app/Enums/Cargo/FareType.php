<?php

namespace App\Enums\Cargo;

class FareType
{
    public const RESERVE = 'reserve';
    public const RFQ = 'rfq';
    public const FREE = 'free';

    public const TYPES = [
        self::RESERVE,
        self::RFQ,
        self::FREE
    ];
}
