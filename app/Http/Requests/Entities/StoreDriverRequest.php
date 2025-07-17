<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
        return [
            'user_id' => foreign_id_rules('users', true),
            'national_code' => iranian_national_code_rules(true),
            'birth_date' => ['nullable', 'date', 'before:today'],
            'father_name' => string_rules(false),
            'certificate_number' => string_rules(false),
            'property' => enum_rules(\App\Enums\Entity\PropertyType::TYPES, false),
            'national_card_file' => file_rules(false),
            'smart_card_file' => file_rules(false),
            'certificate_file' => file_rules(false),
            'company_id' => foreign_id_rules('companies', false),
            'city_id' => foreign_id_rules('cities', false),
        ];
    }
}
