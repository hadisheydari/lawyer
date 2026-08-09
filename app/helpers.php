<?php

use App\Services\PersianNumberService;

if (! function_exists('fa_digits')) {
    function fa_digits($number): string
    {
        return PersianNumberService::toPersianDigits($number);
    }
}

if (! function_exists('fa_number')) {
    // جایگزین number_format — با جداکننده و رقم فارسی
    function fa_number($number, string $separator = '٬'): string
    {
        return PersianNumberService::format($number, $separator);
    }
}

if (! function_exists('fa_number_words')) {
    function fa_number_words($number): string
    {
        return PersianNumberService::toWords($number);
    }
}

if (! function_exists('fa_price')) {
    function fa_price($number, string $unit = 'تومان'): string
    {
        return PersianNumberService::format($number) . ' ' . $unit;
    }
}

if (! function_exists('fa_price_words')) {
    function fa_price_words($number, string $unit = 'تومان'): string
    {
        return PersianNumberService::toWordsWithUnit($number, $unit);
    }
}