<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class CargoTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('cargo_type')?->id;

        return [
            'name' => string_rules(true, 2, 255), // رشته الزامی با طول بین ۲ تا ۲۵۵
            'code' => array_merge(unsigned_integer_rules(true), [
            ]),
        ];
    }
}
