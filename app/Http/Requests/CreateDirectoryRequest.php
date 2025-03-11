<?php

namespace App\Http\Requests;

use App\Models\FileSystem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreateDirectoryRequest extends FormRequest
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
        $id = request()->input('id', null);
        $parentDirectoryID = request()->input('parent_id');

        return [
            "name" => [
                "required",
                "string",
                Rule::unique('file_systems', 'name')->where(function ($query) use ($parentDirectoryID) {
                    return $query->where("type", "directory")
                        ->where("parent_id", $parentDirectoryID);
                })->ignore($id)
            ],
            "slug" => ["required", "string"],
            "path" => ["required", "string"],
            "type" => ["required", "string"],
            "parent_id" => [],
        ];
    }


    protected function prepareForValidation()
    {

        $this->merge([
            "slug" => Str::slug($this->name),
            "type" => "directory"
        ]);

        if (isset($this->parent_id)) {

            $parentDir = FileSystem::find($this->parent_id);

            $path = $parentDir ? $parentDir->path . '/' . $this->slug : "uploads/" . $this->slug;

            $this->merge([
                "path" => $path
            ]);
        } else {
            $this->merge([
                "path" => "uploads/" . $this->slug
            ]);
        }
    }
}
