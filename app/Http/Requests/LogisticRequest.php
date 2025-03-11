<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogisticRequest extends FormRequest
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

        $rules = [];

        // if (!request()->input("id")) {
        $rules = [
            "status" => ["required", "in:Pending,In Shipment,Completed,Delayed,Canceled"],
        ];
        // }

        return [
            ...$rules,
            "client_id" => ["required", "integer"],
            "project_id" => ["required", "integer"],
            "manager_id" => ["required", "integer"],
            "assigned_to" => ["required", "integer"],
            "registration_date" => ["required", "date"],
            "shipment_date" => ["required", "date"],
            "notes" => ["nullable", "string", "max:10000"],
        ];
    }
}
