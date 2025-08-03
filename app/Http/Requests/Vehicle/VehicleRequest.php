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
            'smart_number'      => ['sometimes', ...numeric_rules()],
            'cost_center'       => ['sometimes', ...numeric_rules()],
            'plate_first'       => ['sometimes', ...numeric_rules(false ,10 , 99)],
            'plate_second'      => ['sometimes', ...numeric_rules(false ,100,999)],
            'plate_third'       => ['sometimes', ...numeric_rules(false ,10 , 99)],
            'plate_letter'      => ['sometimes', ...enum_rules(PlateLatter::LETTERS)],
            'plate_type'        => ['sometimes', ...enum_rules(PlateType::TYPES)],
            'status'            => ['sometimes', ...enum_rules(Status::STATUSES)],
            'vehicle_detail_id' => ['sometimes', ...foreign_id_rules('vehicle_details' , false)],
            'driver_id'         => ['sometimes', ...foreign_id_rules('drivers' , false)],
        ];
    }
}
