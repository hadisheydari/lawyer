<?php

namespace App\Http\Requests\CargoDelivery;

use App\Enums\Cargo\CargoStatus;
use App\Enums\Entity\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cargo;
class PartitionRequest extends FormRequest
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
            'cargo_id'=> foreign_id_rules('cargos' , true),
            'driver_id'=> foreign_id_rules('users' , false),
            'company_id' => foreign_id_rules('users' , false),
            'vehicle_detail_id'  => foreign_id_rules('vehicle_details' , false),
            'weight'=> numeric_rules(true , 1 , 65534),
            'fare'=>  numeric_rules(),
            'commission'=>   numeric_rules(),
            'status'=>  enum_rules(CargoStatus::STATUSES , false),
            'property'=> enum_rules(PropertyType::TYPES , false),
            'havaleFile' => file_rules(false),
            'barnamehFile' => file_rules(false),

        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cargoId      = $this->input('cargo_id');
            $newWeight    = (float) $this->input('weight');
            $partitionWeight  = $this->route('partition')->weight ?? 0;

            if (!$cargoId || !$newWeight) {
                return;
            }

            $cargo = Cargo::with('partitions')->find($cargoId);

            if (!$cargo) {
                return;
            }


            $totalWeight = ($cargo->partition_weight - $partitionWeight) + $newWeight;

            $maxWeight = $cargo->weight;

            if ($totalWeight > $maxWeight) {
                $validator->errors()->add(
                    'weight',
                    'وزن وارد شده بیشتر از ظرفیت کل محموله است. (حداکثر: ' . ($maxWeight - ($cargo->partition_weight - $partitionWeight)) . ')'
                );
            }

        });
    }

}
