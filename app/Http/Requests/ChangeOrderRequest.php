<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrderRequest extends FormRequest
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
            // "client_id" => ["required", "integer"],
            "project_id" => ["required", "integer"],
            "date" => ["required", "date"],
            "floor" => ["required", "string"],
            "unit_number" => ["required", "numeric"],
            "description_of_change" => ["required", "string", "max:10000"],
            "description_of_change_approval" => ["nullable", "string", "min:0", "max:10000"],
            "reason_for_change" => ["required", "string", "max:10000"],
            "reason_for_change_approval" => ["nullable", "string", "min:0", "max:10000"],
            "additional_notes" => ["nullable", "string", "min:0", "max:10000"],
            "fix_amount" => ["required", "boolean"],
            "charge_amount" => ["required", "string", "min:0"],
            "york_signature" => ["nullable", "string"],
            "client_signature" => ["nullable", "string"],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'fix_amount' => isset($this->fix_amount) && $this->fix_amount == 1 ? true : false,
        ]);
    }
}
