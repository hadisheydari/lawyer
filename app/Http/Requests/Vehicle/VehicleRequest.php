<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Vehicle\PlateLatter;
use App\Enums\Vehicle\PlateType;
use App\Enums\Vehicle\Status;
class VehicleRequest extends FormRequest
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
            'smart_number' => numeric_rules(),
            'cost_center' => numeric_rules() ,
            'plate_first' => numeric_rules(false ,10 , 99),
            'plate_second' => numeric_rules(false ,100,999),
            'plate_third' => numeric_rules(false ,10 , 99),
            'plate_letter' => enum_rules(PlateLatter::LETTERS),
            'plate_type' => enum_rules(PlateType::TYPES),
            'status' => enum_rules(Status::STATUSES),
            'vehicle_detail_id' => foreign_id_rules('vehicle_details' , false),
            'driver_id ' => foreign_id_rules('drivers' , false),
        ];
    }
}
