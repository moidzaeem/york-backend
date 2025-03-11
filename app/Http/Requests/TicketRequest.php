<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            "subject" => ["required", "string"],
            "category_id" => ["required", "integer"],
            "owner_id" => ["required", "integer"],
            "status" => ["required", "in:New,Solved,Accepted,Not Accepted,Delayed"],
            "description" => ["nullable", "string", "max:10000"],
            "files" => ["array"],
            "files.*" => [
                'file',
                'mimes:jpeg,png,jpg,gif,pdf,doc,docx,ppt,pptx',
                'max:12288', // 12 MB in kilobytes
            ],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'owner_id' => auth()->user()->id,
            'status' => $this->input("status", null) ?? "New"
        ]);
    }
}
