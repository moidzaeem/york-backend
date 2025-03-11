<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class LearningMaterialRequest extends FormRequest
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
            "title" => [
                "required",
                "string",
                Rule::unique('learning_materials')->ignore($id)
            ],
            "category_id" => ["required", "integer"],
            "files" => ["array"],
            "files.*" => [
                'file',
                'mimes:jpeg,png,jpg,gif,pdf,doc,docx,ppt,pptx',
                'max:12288', // 12 MB in kilobytes
            ],
        ];
    }
}
