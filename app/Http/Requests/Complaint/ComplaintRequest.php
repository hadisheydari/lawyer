<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Complaint\Status;
class ComplaintRequest extends FormRequest
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
            'complainant_id' => foreign_id_rules('users' , false),
            'receiver_id' => foreign_id_rules('users' , false),
            'title' => string_rules(true, 2, 255),
            'description' => string_rules(true, 2, 255),
            'status' => enum_rules(Status::STATUSES),
        ];
    }
}
