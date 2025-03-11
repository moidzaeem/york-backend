<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class InstallationRequest extends FormRequest
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
            "project_id" => ["required", "integer"],
            "assigned_to" => ["required", "integer"],
            "manager_id" => ["required", "integer"],
            "installation_amount" => ["required", "string", "min:0"],
            "shipment_amount" => ["required", "string", "min:0"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date"],
            "status" => ["required", "in:Pending,Canceled,Completed,Postponed,Ongoing"],
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
