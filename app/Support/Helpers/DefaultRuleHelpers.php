<?php

use App\Models\User;
use Illuminate\Validation\Rule;

if (! function_exists('string_rules')) {
    /**
     * Generate validation rules for string inputs.
     *
     * @param  bool  $required  Whether the field is required.
     * @param  int  $min  Minimum string length.
     * @param  int  $max  Maximum string length.
     * @return array<string> Validation rule array.
     */
    function string_rules(bool $required = false, int $min = 0, int $max = 255): array
    {
        $rules = ['string', "min:$min", "max:$max"];
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}

if (! function_exists('username_rules')) {
    /**
     * Default username validation rules.
     *
     * @param  int|null  $ignoreId  If set, will ignore unique for the given user ID (for update).
     * @return array<string|\Illuminate\Contracts\Validation\Rule>
     */
    function username_rules(bool $required = true, ?int $ignoreId = null): array
    {
        $rules = ['string', 'min:3', 'max:255', 'alpha_dash'];
        $rules[] = $required ? 'required' : 'nullable';

        $unique = Rule::unique(User::class, 'username');
        if ($ignoreId) {
            $unique->ignore($ignoreId);
        }

        $rules[] = $unique;

        return $rules;
    }
}

if (! function_exists('phone_rules')) {
    /**
     * Validation rules for Iranian phone numbers.
     *
     * @param  bool  $required  Whether the field is required.
     * @param  int|null  $ignoreId  ID to ignore for unique check (useful for updates).
     * @return array<string|\Illuminate\Contracts\Validation\Rule>
     */
    function phone_rules(bool $required = true, ?int $ignoreId = null): array
    {
        $rules = [
            'string',
            'max:11',
            'regex:/^09\d{9}$/',
        ];

        $rules[] = $required ? 'required' : 'nullable';

        $unique = Rule::unique(User::class, 'phone');
        if ($ignoreId) {
            $unique->ignore($ignoreId);
        }

        $rules[] = $unique;

        return $rules;
    }
}

if (! function_exists('password_rules')) {
    /**
     * Password validation rules.
     *
     * Laravel >= 9 supports Password::defaults() but here we define our own rules.
     *
     * @return array<string>
     */
    function password_rules(bool $required = true): array
    {
        $rules = ['string', 'min:8', 'max:255'];
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}
