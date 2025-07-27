<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Entity\CompanyType;


class CompanyRequest extends FormRequest
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
            'company_type' => enum_rules(CompanyType::TYPES),
            'registration_id' => numeric_rules(false),
            'national_id' => numeric_rules(false),
            'rahdari_code' => numeric_rules(false),
            'agent_name' => string_rules(),
            'agent_national_code' => iranian_national_code_rules(false),
            'agent_phone_number' => phone_rules(true, null, false),
            'manager_name' => string_rules(),
            'manager_national_code' => iranian_national_code_rules(false),
            'manager_phone_number' => phone_rules(true, null, false),
            'address' => string_rules(),
            'document' => file_rules(true, ['jpg', 'jpeg', 'png', 'pdf']),
            'city_id' => foreign_id_rules('cities', true),
            'province_id' => foreign_id_rules('provinces' , true),

        ];

        if ($this->isMethod('post')) {
            $rules['conditions'] = ['accepted'];
        }

        return $rules;
    }
}
