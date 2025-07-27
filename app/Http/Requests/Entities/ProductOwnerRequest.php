<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Entity\ProductOwnerType;


class ProductOwnerRequest extends FormRequest
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
        $rules = [
            'city_id' => foreign_id_rules('cities', true),
            'province_id' => foreign_id_rules('provinces' , true),
            'product_owner_type' => enum_rules(ProductOwnerType::TYPES, false),
            'national_code' => iranian_national_code_rules(true),
            'bank_name' => string_rules(false),
            'sheba_number' => sheba_rules(),
            'address' => string_rules(false),
            'document' => file_rules(true, ['jpg', 'jpeg', 'png', 'pdf']),
            'registration_id' => numeric_rules(false),
            'national_id' => numeric_rules(false),
            'rahdari_code' => numeric_rules(false),
            'agent_name' => string_rules(false),
            'agent_national_code' => iranian_national_code_rules(false),
            'agent_phone_number' => phone_rules(false),
            'manager_name' => string_rules(false),
            'manager_national_code' => iranian_national_code_rules(false),
            'manager_phone_number' => phone_rules(false),

        ];

        if ($this->input('product_owner_type') === 'legal') {
            $rules['registration_id'] = numeric_rules(true);
            $rules['national_id'] = numeric_rules(true);
            $rules['rahdari_code'] = numeric_rules(true);
            $rules['agent_name'] = string_rules(true);
            $rules['agent_national_code'] = iranian_national_code_rules(true);
            $rules['agent_phone_number'] = phone_rules(true);
            $rules['manager_name'] = string_rules(true);
            $rules['manager_national_code'] = iranian_national_code_rules(true);
            $rules['manager_phone_number'] = phone_rules(true);
        }

        if ($this->isMethod('post')) {
            $rules['conditions'] = ['accepted'];
        }

        return $rules;
    }

}
