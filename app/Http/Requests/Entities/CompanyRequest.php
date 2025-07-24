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
            'user_id' => foreign_id_rules('users', true),
            'company_type' => enum_rules(CompanyType::TYPES),
            'registration_id' => string_rules(),
            'national_id' => string_rules(),
            'rahdari_code' => string_rules(),
            'agent_name' => string_rules(),
            'agent_national_code' => iranian_national_code_rules(false),
            'agent_phone_number' => phone_rules(false),
            'manager_name' => string_rules(),
            'manager_national_code' => iranian_national_code_rules(false),
            'manager_phone_number' => phone_rules(false),
            'address' => string_rules(),
            'document' => file_rules(false, ['jpg', 'jpeg', 'png', 'pdf']),
            'city_id' => foreign_id_rules('cities'),
        ];

        if ($this->isMethod('post')) {
            $rules['terms'] = ['accepted'];
        }

        return $rules;
    }
}
