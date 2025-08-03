<?php

namespace App\Http\Requests\CargoDeclaration;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Cargo\FareType;
use App\Enums\Cargo\Type;


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
            'owner_id' => foreign_id_rules('product_owners' , true),
            'weight' => numeric_rules(),
            'number' => numeric_rules(),
            'thickness' => numeric_rules(),
            'length' => numeric_rules(),
            'width' => numeric_rules(),
            'insurance' => numeric_rules(false),
            'fare' => numeric_rules(),
            'fare_type' => enum_rules(FareType::TYPES),
            'type' => enum_rules(Type::TYPES),
            'cargo_type_id' => foreign_id_rules('cargo_types' , true),
            'packing_id' => foreign_id_rules('packings' , true),
            'assigned_company_id' => foreign_id_rules('users' , false),
            'description' => string_rules(),

            'origin.province_id' => foreign_id_rules('provinces' , true),
            'origin.city_id' => foreign_id_rules('cities' , true),
            'origin.lat' => numeric_rules(),
            'origin.lng' => numeric_rules(),
            'origin.address' => string_rules(),
            'origin.description' => string_rules(false),
            'origin.date_at' => gregorian_datetime_rules(),

            'destination.province_id' => foreign_id_rules('provinces' , true),
            'destination.city_id' => foreign_id_rules('cities' , true),
            'destination.lat' => numeric_rules(),
            'destination.lng' => numeric_rules(),
            'destination.address' => string_rules(),
            'destination.description' => string_rules(false),
            'destination.date_to' => gregorian_datetime_rules(),

        ];
    }
}
