<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenderRequest extends FormRequest
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
            "project_name" => [
                "required",
                "string",
                Rule::unique('tenders', 'project_name')->ignore($id),
            ],
            "client_id" => ["required", "integer"],
            "category_id" => ["required", "integer"],
            "closing_date" => ["required", "date"],
            "status" => ["required", "in:Offer Made,Pending,Won,Lost"],
            "notes" => ["nullable", "string", "max:10000"],
        ];
    }
}
