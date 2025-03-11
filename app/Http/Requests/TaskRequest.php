<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            "title" => ["required", "string", "min:3", "max:35"],  // Add min and max length for better validation
            "assigned_to" => [
                "required",
                "array",               // Ensure it's an array
                "min:1",               // Ensure at least one user is assigned
                function ($attribute, $value, $fail) {  // Custom rule to check each item in the array
                    foreach ($value as $userId) {
                        if (!$this->isValidUser($userId)) {
                            $fail("Each selected assignee must be a valid user ID.");
                        }
                    }
                }
            ],
            "client_id" => ["required", "integer", "exists:users,id"],  // Ensure the client exists in the users table
            "department_id" => ["required", "integer", "exists:departments,id"],  // Ensure department exists
            "category_id" => ["required", "integer", "exists:categories,id"],  // Ensure category exists
            "start_date" => ["required", "date", "before:end_date"],  // Ensure start_date is before end_date
            "end_date" => ["required", "date", "after:start_date"],  // Ensure end_date is after start_date
            "status" => ["required", "in:0%,10%,20%,30%,40%,50%,60%,70%,80%,90%,100%"],
            "notes" => ["nullable", "string", "max:10000"],
            "priority" => ["required", "in:High,Medium,Normal,Low"],
        ];
    }

    /**
     * Custom helper method to check if the user exists.
     */
    protected function isValidUser($userId)
    {
        return \App\Models\User::find((int) $userId) !== null;    }
}
