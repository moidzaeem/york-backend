<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
        $id = request()->input("id");

        return [
            "event_name" => [
                "required",
                "string",
                Rule::unique('events', 'event_name')->ignore($id),
            ],
            "client_id" => ["required", "integer"],
            "assigned_to" => ["required", "integer"],
            "due_date" => ["required", "date"],
            "department_id" => ["required", "integer"],
            "status" => ["required", "in:Pending,Completed,Canceled,Failed,Postponed"],
            "notes" => ["nullable", "string", "max:10000"]
        ];
    }
}
