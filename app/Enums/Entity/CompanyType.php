<?php


namespace App\Enums\Entity;

class CompanyType
{
    public const NORMAL = 'normal';
    public const LARGE_SCALE = 'large_scale';

    public const TYPES = [
        self::NORMAL,
        self::LARGE_SCALE,
    ];
}
