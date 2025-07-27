<?php

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\CargoType;
use App\Models\Packing;

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
     * @param  bool  $required   Whether the field is required.
     * @param  int|null  $ignoreId  ID to ignore for unique check (useful for updates).
     * @param  bool  $checkUnique  Whether to check for unique phone (e.g., for register).
     * @return array<string|\Illuminate\Contracts\Validation\Rule>
     */
    function phone_rules(bool $required = true, ?int $ignoreId = null, bool $checkUnique = true): array
    {
        $rules = [
            'string',
            'max:11',
            'regex:/^09\d{9}$/',
        ];

        $rules[] = $required ? 'required' : 'nullable';

        // فقط اگر چک یکتا بودن لازمه
        if ($checkUnique) {
            $unique = Rule::unique(User::class, 'phone');
            if ($ignoreId) {
                $unique->ignore($ignoreId);
            }
            $rules[] = $unique;
        }

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


if (! function_exists('otp_code_rules')) {
    /**
     * OTP code validation rules.
     *
     * @param  bool  $required  Whether the field is required.
     * @return array<string>
     */
    function otp_code_rules(bool $required = true): array
    {
        $rules = ['digits:6']; // یا 'numeric|min:100000|max:999999'
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}


if (! function_exists('unsigned_integer_rules')) {
    /**
     * Rules for unsigned integers (nullable or required).
     *
     * @param bool $required
     * @param int|null $max
     * @return array<string>
     */
    function unsigned_integer_rules(bool $required = false, ?int $max = null): array
    {
        $rules = ['integer', 'min:0'];
        if ($max) {
            $rules[] = "max:$max";
        }
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}

if (! function_exists('enum_rules')) {
    /**
     * Rules for enums (Laravel Rule::in).
     *
     * @param array $values
     * @param bool $required
     * @return array<string|\Illuminate\Contracts\Validation\Rule>
     */
    function enum_rules(array $values, bool $required = true): array
    {
        $rules = [Rule::in($values)];
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}

if (! function_exists('foreign_id_rules')) {
    /**
     * Rules for foreign IDs.
     *
     * @param string $table
     * @param bool $required
     * @return array<string|\Illuminate\Contracts\Validation\Rule>
     */
    function foreign_id_rules(string $table, bool $required = false): array
    {
        $rules = ['integer', "exists:$table,id"];
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}

if (! function_exists('iranian_national_code_rules')) {
    function iranian_national_code_rules(bool $required = true): array
    {
        $rules = ['regex:/^\d{9,12}$/'];
        // فقط 10 رقم
        $rules[] = $required ? 'required' : 'nullable';
        return $rules;
    }
}

if (! function_exists('file_rules')) {
    function file_rules(bool $required = false, array $mimes = ['jpg','jpeg','png','pdf'], int $max = 2048): array
    {
        $rules = ['file', 'mimes:' . implode(',', $mimes), "max:$max"];
        $rules[] = $required ? 'required' : 'nullable';
        return $rules;
    }
}

if (! function_exists('date_rules')) {
    /**
     * Rules for date fields.
     *
     * @param bool $required
     * @return array<string>
     */
    function date_rules(bool $required = false): array
    {
        $rules = ['date'];
        $rules[] = $required ? 'required' : 'nullable';
        return $rules;
    }
}

if (! function_exists('sheba_rules')) {
    /**
     * Rules for an unsigned big integer or Iranian Sheba (optional IR prefix).
     *
     * @param bool $required
     * @return array<string>
     */
    function sheba_rules(bool $required = false): array
    {
        $rules = [
            'numeric',
            'min:0',
        ];
        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}

if (! function_exists('gregorian_datetime_rules')) {
    /**
     * Validate a Gregorian datetime in format Y-m-d H:i:s.
     *
     * @param bool $required
     * @return array<string>
     */
    function gregorian_datetime_rules(bool $required = true): array
    {
        $rules = [
            'regex:/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            'date'
        ];
        $rules[] = $required ? 'required' : 'nullable';
        return $rules;
    }
}

if (! function_exists('numeric_rules')) {
    /**
     * Rules for numeric values.
     *
     * @param bool $required  Whether the field is required.
     * @param float|int|null $min  Minimum numeric value.
     * @param float|int|null $max  Maximum numeric value.
     * @return array<string>
     */
    function numeric_rules(bool $required = false, $min = null, $max = null): array
    {
        $rules = ['numeric'];

        if (!is_null($min)) {
            $rules[] = "min:$min";
        }

        if (!is_null($max)) {
            $rules[] = "max:$max";
        }

        $rules[] = $required ? 'required' : 'nullable';

        return $rules;
    }
}
