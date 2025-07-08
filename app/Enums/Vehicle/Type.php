<?php

namespace App\Enums\Vehicle;

class Type
{
    public const VEHICLE = 'vehicle';
    public const TRAILER = 'trailer';

    public const TYPES = [
        self::VEHICLE,
        self::TRAILER,
    ];
}
