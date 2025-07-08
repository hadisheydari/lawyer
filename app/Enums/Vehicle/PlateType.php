<?php

namespace App\Enums\Vehicle;

class PlateType
{
    public const GENERAL = 'general';
    public const GOVERNMENTAL = 'governmental';
    public const PERSONAL = 'personal';

    public const TYPES = [
        self::GENERAL,
        self::GOVERNMENTAL,
        self::PERSONAL,
    ];
}
