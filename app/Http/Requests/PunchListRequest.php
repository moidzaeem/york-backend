<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PunchListRequest extends FormRequest
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
            "manager_id" => ["required", "integer"],
            "assigned_to" => ["required", "integer"],
            "end_date" => ["required", "date"],
            "address" => ["required", "string"],
            "priority" => ["required", "in:High,Medium,Normal,Low"],
            "status" => ["required", "in:Pending,Canceled,Completed,Postponed,Ongoing"],
            "notes" => ["nullable", "string", "max:10000"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "end_date" => $this->date,
            "manager_id" => 1, // manager_id must be removed from punchList model
        ]);
    }
}
