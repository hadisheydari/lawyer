<?php

namespace App\Http\Requests\CargoDeclaration;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Cargo\ReservationStatus;


class CargoReservationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'cargo_id' => foreign_id_rules('cargos', true),

            'company_id' => ['required', function ($attribute, $value, $fail) {
                if (!is_array($value) && !is_numeric($value)) {
                    $fail("$attribute باید عدد یا آرایه‌ای از اعداد باشد.");
                }
            }],

            'company_id.*' => foreign_id_rules('users', true),
            'status' => enum_rules(ReservationStatus::STATUSES, false),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cargoId = $this->cargo_id;
            $companies = $this->company_id;

            if (!is_array($companies)) {
                $companies = [$companies];
            }

            foreach ($companies as $companyId) {
                $exists = \DB::table('cargo_reservations')->where('cargo_id', $cargoId)->where('company_id', $companyId)->exists();

                if ($exists) {
                    $validator->errors()->add('company_id', "شرکت حمل قبلاً برای این بار رزرو شده است.");
                }
            }
        });
    }
}
