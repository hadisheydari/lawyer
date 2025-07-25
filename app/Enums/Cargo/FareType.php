<?php

namespace App\Enums\Cargo;

class FareType
{
    public const SERVICE = 'service';
    public const TONNAGE = 'tonnage';

    public const TYPES = [
        self::SERVICE,
        self::TONNAGE
    ];

}
