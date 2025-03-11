<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
        $id = request()->input('id');
        $clientID = request()->input('client_id');

        return [
            "name" => ["required", "string"],
            "client_name" => ["required", "string"],
            "company_name" => ["required", "string"],
            "phone_gsm" => [
                "required",
                "string",
                Rule::unique('clients', 'phone_gsm')->ignore($clientID)
            ],
            "phone_stable" => [
                "required",
                "string",
                Rule::unique('clients', 'phone_stable')->ignore($clientID)
            ],
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
            "password" => [
                "required",
                "string",
                "min:8"
            ],
            "special" => "",
            "blacklist" => "",
            "status" => ["required"],
        ];
    }


    protected function prepareForValidation()
    {

        $this->merge([
            "special" => isset($this->decorated) && $this->decorated == "special" ? true : false,
            "blacklist" => isset($this->decorated) && $this->decorated == "blacklist" ? true : false,
            // "special" => isset($this->special) && $this->special == "true" ? true : false,
            // "blacklist" => isset($this->blacklist) && $this->blacklist == "true" ? true : false,
            "phone_number" => $this->phone_gsm,
            "name" => $this->client_name,
            "status" => isset($this->status) ? $this->status : "Pending"
        ]);

        if (!isset($this->password)) {

            $this->merge([
                "password" => "Ignore this password",
            ]);
        }
    }
}
