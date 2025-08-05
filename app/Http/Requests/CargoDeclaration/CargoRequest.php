<?php

namespace App\Http\Requests\CargoDeclaration;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Cargo\FareType;
use App\Enums\Cargo\Type;
use App\Enums\Cargo\LocationType;


class CargoRequest extends FormRequest
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
            'weight' => numeric_rules(true , 1 , 65534),
            'number' => numeric_rules(true , 1 , 65534),
            'thickness' => numeric_rules(true , 1 , 26),
            'length' => numeric_rules(true , 1 , 26),
            'width' => numeric_rules(true , 1 , 26),
            'insurance_id' => foreign_id_rules('insurances' , true),
            'insurance_value' => numeric_rules(false),
            'fare' => numeric_rules(),
            'final_fare' => numeric_rules(),
            'fare_type' => enum_rules(FareType::TYPES),
            'type' => enum_rules(Type::TYPES , false),
            'cargo_type_id' => foreign_id_rules('cargo_types' , true),
            'packing_id' => foreign_id_rules('packings' , true),
            'assigned_company_id' => foreign_id_rules('users' , false),
            'description' => string_rules(),

            'origin.type' => enum_rules(LocationType::TYPES),
            'origin.province_id' => foreign_id_rules('provinces' , true),
            'origin.city_id' => foreign_id_rules('cities' , true),
            'origin.lat' => numeric_rules(),
            'origin.lng' => numeric_rules(),
            'origin.address' => string_rules(),
            'origin.description' => string_rules(false),
            'origin.date_at' => gregorian_datetime_rules(false),

            'destination.type' => enum_rules(LocationType::TYPES),
            'destination.province_id' => foreign_id_rules('provinces' , true),
            'destination.city_id' => foreign_id_rules('cities' , true),
            'destination.lat' => numeric_rules(),
            'destination.lng' => numeric_rules(),
            'destination.address' => string_rules(),
            'destination.description' => string_rules(false),
            'destination.date_to' => gregorian_datetime_rules(false),

        ];
    }
}
