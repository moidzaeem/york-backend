<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class EmployeeRequest extends FormRequest
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
        $id = request()->input('user_id');

        Log::info("trying to update employee no. {$id}");

        return [
            "name" => ["required", "string"],
            "phone_number" => [
                "required",
                "string",
                Rule::unique('users', 'phone_number')->ignore($id)
            ],
            "email" => [
                "required",
                "string",
                "email",
                Rule::unique('users', 'email')->ignore($id)
            ],
            "address" => ["required", "string"],
            "position_id" => ["required", "integer"],
            "department_id" => ["required", "integer"],
            "employment_type" => ["required", "string"],
            "dob" => ["required", "date"],
            "notes" => ["nullable", "string", "min:0", "max:10000"],
            "role" => ["required", "string"],
            "permissions" => ["array"],
            "permissions.*" => ["string"],
            "files" => ["array"],
            "files.*" => [
                File::image()
                    ->max(12 * 1024),
            ]
        ];
    }


    protected function prepareForValidation(): void
    {
        // $this->merge([
        //     // 
        // ]);
    }
}
