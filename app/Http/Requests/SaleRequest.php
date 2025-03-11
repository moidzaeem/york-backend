<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class SaleRequest extends FormRequest
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
            "client_id" => ["required", "integer"],
            "sales_person_id" => ["required", "integer"],
            "category_id" => ["required", "integer"],
            "offer_price" => ["required", "numeric", "min:0"],
            "date" => ["required", "date"],
            "status" => ["required", "in:Bid Given,Bid Not Given,Accepted,Rejected"],
            "notes" => ["nullable", "string", "max:10000"],
            "files" => ["array"],
            "files.*" => [
                'file',
                'mimes:jpeg,png,jpg,gif,pdf,doc,docx,ppt,pptx',
                'max:12288', // 12 MB in kilobytes
            ],
        ];
    }
}
