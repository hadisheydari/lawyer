<?php

namespace App\Enums\Rating;


enum RatingEnum: int
{
    case Excellent = 5;
    case VeryGood  = 4;
    case Good      = 3;
    case Bad       = 2;
    case VeryBad   = 1;

    public function label(): string
    {
        return match($this) {
            self::Excellent => 'Excellent',
            self::VeryGood  => 'Very Good',
            self::Good      => 'Good',
            self::Bad       => 'Bad',
            self::VeryBad   => 'Very Bad',
        };
    }

    // همه مقادیر برای migration یا validation
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}

