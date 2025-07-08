<?php

namespace App\Enums\Cargo;

class LocationType
{
    public const ORIGIN = 'origin';
    public const DESTINATION = 'destination';

    public const TYPES = [
        self::ORIGIN,
        self::DESTINATION
    ];
}
