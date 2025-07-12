<?php

namespace App\Enums\Cargo;

class CargoStatus
{
    public const FREE = 'free';
    public const RESERVED = 'reserved';
    public const HAVALE = 'havale';
    public const BARNAMEH = 'barnameh';
    public const DELIVERED = 'delivered';

    public const STATUSES = [
        self::FREE,
        self::RESERVED,
        self::HAVALE,
        self::BARNAMEH,
        self::DELIVERED
    ];
}
