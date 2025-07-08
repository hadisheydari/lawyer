<?php

namespace App\Enums\Entity;

class PropertyType
{
    public const OWNED = 'owned';
    public const NON_OWNED = 'non_owned';

    public const TYPES = [
        self::OWNED,
        self::NON_OWNED,
    ];
}
