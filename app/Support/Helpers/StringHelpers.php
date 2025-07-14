<?php

if (! function_exists('enToFa')) {
    /**
     * Converts English digits to Persian digits.
     */
    function enToFa(string $string): string
    {
        return str_replace(
            range('0', '9'),
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            $string
        );
    }
}

if (! function_exists('faToEn')) {
    /**
     * Converts Persian digits to English digits.
     */
    function faToEn(string $string): string
    {
        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            range('0', '9'),
            $string
        );
    }
}

if (! function_exists('secretEmail')) {
    /**
     * Obfuscates an email address by hiding part of the local part.
     *
     * Example: johndoe@example.com → j******@example.com
     */
    function secretEmail(string $email): string
    {
        return preg_replace_callback('/^(.)(.*)(@.+)$/', fn ($matches) => $matches[1].str_repeat('*', strlen($matches[2])).$matches[3], $email);
    }
}
