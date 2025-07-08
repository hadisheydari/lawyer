<?php

namespace App\Enums\Complaint;

class Status
{
    public const READ = 'read';
    public const NOT_READ = 'not_read';

    public const STATUSES = [
        self::READ,
        self::NOT_READ,
    ];
}
