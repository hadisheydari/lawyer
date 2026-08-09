<?php

namespace App\Services;

class PersianNumberService
{
    protected static array $ones = [
        '', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه',
    ];

    protected static array $teens = [
        'ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده',
        'شانزده', 'هفده', 'هجده', 'نوزده',
    ];

    protected static array $tens = [
        '', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود',
    ];

    protected static array $hundreds = [
        '', 'صد', 'دویست', 'سیصد', 'چهارصد', 'پانصد',
        'ششصد', 'هفتصد', 'هشتصد', 'نهصد',
    ];

    protected static array $scales = [
        '', 'هزار', 'میلیون', 'میلیارد', 'بیلیون', 'تریلیون', 'بیلیارد',
    ];

    /**
     * تبدیل ارقام انگلیسی به فارسی
     */
    public static function toPersianDigits($number): string
    {
        return strtr((string) $number, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }

    /**
     * جداکننده سه‌رقمی + رقم فارسی
     * 1500000 => ۱٬۵۰۰٬۰۰۰
     */
    public static function format($number, string $separator = '٬'): string
    {
        if ($number === null || $number === '') {
            return '';
        }

        $number     = (float) $number;
        $isNegative = $number < 0;
        $intPart    = number_format(abs($number), 0, '.', $separator);
        $result     = self::toPersianDigits($intPart);

        return $isNegative ? '−' . $result : $result;
    }

    /**
     * تبدیل عدد به حروف فارسی
     * 1500000 => یک میلیون و پانصد هزار
     */
    public static function toWords($number): string
    {
        $number = (int) round((float) $number);

        if ($number === 0) {
            return 'صفر';
        }

        $isNegative = $number < 0;
        $number     = abs($number);

        $groups = [];
        while ($number > 0) {
            $groups[] = $number % 1000;
            $number   = (int) floor($number / 1000);
        }

        $parts = [];
        for ($i = count($groups) - 1; $i >= 0; $i--) {
            $group = $groups[$i];
            if ($group === 0) {
                continue;
            }
            $scale   = self::$scales[$i] ?? '';
            $parts[] = trim(self::threeDigitToWords($group) . ($scale ? ' ' . $scale : ''));
        }

        $result = implode(' و ', $parts);

        return $isNegative ? 'منفی ' . $result : $result;
    }

    /**
     * حروف فارسی + واحد (پیش‌فرض تومان)
     */
    public static function toWordsWithUnit($number, string $unit = 'تومان'): string
    {
        return trim(self::toWords($number) . ' ' . $unit);
    }

    protected static function threeDigitToWords(int $number): string
    {
        $words     = [];
        $hundred   = (int) floor($number / 100);
        $remainder = $number % 100;

        if ($hundred > 0) {
            $words[] = self::$hundreds[$hundred];
        }

        if ($remainder > 0) {
            if ($remainder < 10) {
                $words[] = self::$ones[$remainder];
            } elseif ($remainder < 20) {
                $words[] = self::$teens[$remainder - 10];
            } else {
                $ten = (int) floor($remainder / 10);
                $one = $remainder % 10;
                $words[] = $one > 0
                    ? self::$tens[$ten] . ' و ' . self::$ones[$one]
                    : self::$tens[$ten];
            }
        }

        return implode(' و ', $words);
    }
}