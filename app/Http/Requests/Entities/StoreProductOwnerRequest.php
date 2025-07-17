<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductOwnerRequest extends FormRequest
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
            'product_owner_type' => enum_rules(\App\Enums\Entity\ProductOwnerType::TYPES, false),
            'national_code' => iranian_national_code_rules(true),
            'bank_name' => string_rules(false),
            'sheba_number' => ['nullable', 'string', 'regex:/^IR\d{24}$/'],
            'city_id' => foreign_id_rules('cities', false),
            'registration_id' => string_rules(false),
            'national_id' => string_rules(false),
            'rahdari_code' => string_rules(false),
            'agent_name' => string_rules(false),
            'agent_national_code' => iranian_national_code_rules(false),
            'agent_phone_number' => phone_rules(false),
            'manager_name' => string_rules(false),
            'manager_national_code' => iranian_national_code_rules(false),
            'manager_phone_number' => phone_rules(false),
            'address' => string_rules(false, 0, 500),
            'document' => file_rules(false),
        ];
    }
}
