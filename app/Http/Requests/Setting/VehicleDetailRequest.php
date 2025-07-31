<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class VehicleDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand'      => string_rules(true, 2, 255),
            'name'       => string_rules(true, 2, 255),
            'motorCode'  => string_rules(true, 2, 255),
            'bodyCode'   => string_rules(true, 2, 255),
            'year'       => unsigned_integer_rules(true, date('Y') + 1),
        ];
    }
}
