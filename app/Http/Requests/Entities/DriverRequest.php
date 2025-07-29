<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Entity\PropertyType;


class DriverRequest extends FormRequest
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
            'national_code' => iranian_national_code_rules(),
            'birth_date' => gregorian_datetime_rules() ,
            'father_name' => string_rules(),
            'certificate_number' => string_rules(),
            'property' => enum_rules(PropertyType::TYPES, false),
            'national_card_file' => file_rules(false, ['jpg', 'jpeg', 'png']),
            'smart_card_file' => file_rules(false, ['jpg', 'jpeg', 'png']),
            'certificate_file' => file_rules(false, ['jpg', 'jpeg', 'png']),
            'company_id' => foreign_id_rules('companies', false),
            'city_id' => foreign_id_rules('cities' , true),
            'province_id' => foreign_id_rules('provinces' , true),

        ];

        if ($this->input('property') === PropertyType::OWNED) {
            $rules['company_id'] = foreign_id_rules('companies', true);
        }

        if ($this->isMethod('post')) {
            $rules['conditions'] = ['accepted'];
        }

        return $rules;
    }

}
