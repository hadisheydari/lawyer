<?php

namespace App\Enums\Rating;

enum Rating
{
    public const Excellent  = 'Excellent';
    public const VeryGood = 'VeryGood';
    public const Good = 'Good';

    public const Bad = 'Bad';
    public const VeryBad = 'VeryBad';

    public const RATINGS = [
        self::Excellent,
        self::VeryGood,
        self::Good,
        self::Bad,
        self::VeryBad,
    ];}
