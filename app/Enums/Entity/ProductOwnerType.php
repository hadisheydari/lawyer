<?php

namespace App\Enums\Entity;

class ProductOwnerType
{
    public const REAL = 'real';
    public const LEGAL = 'legal';

    public const TYPES = [
        self::REAL,
        self::LEGAL,
    ];
}
