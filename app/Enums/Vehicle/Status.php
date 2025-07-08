<?php

namespace App\Enums\Vehicle;

class Status
{
    public const ACTIVE = 'active';
    public const NOT_ACTIVE = 'not_active';

    public const STATUSES = [
        self::ACTIVE,
        self::NOT_ACTIVE,
    ];
}
