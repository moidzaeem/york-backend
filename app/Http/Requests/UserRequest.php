<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "name" => ["required", "string"],
            "phone_number" => ["required", "string", "unique:users,phone_number,except,{$id}"],
            "email" => ["required", "string", "email", "unique:users,email,except,{$id}"],
            "address" => ["required", "string"],
        ];
    }
    
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            // 
        ]);
    }
}
