<?php

namespace App\Http\Requests;

use App\Models\FileSystem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UploadRequest extends FormRequest
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
            "data" => [],
        ];
    }


    protected function prepareForValidation()
    {

        $directory = FileSystem::find($this->directory);
        $filePath = $directory?->path ?  $directory->path : "uploads";

        if ($this->hasFile("file")) {
            $files = $this->file("file");

            $_files = [];

            $date = now();

            foreach ($files as $file) {

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->storeAs($filePath, $fileName, 'local');

                array_push($_files, [
                    'name' => $file->getClientOriginalName(),
                    'slug' => Str::slug(explode(".", $file->getClientOriginalName())[0]) . "_" . uniqid(),
                    'path' => $filePath . "/" . $fileName,
                    'type' => 'file',
                    'mimetype' => $file->getClientMimeType(),
                    'parent_id' => $directory?->id ?? null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $this->merge([
                "data" => $_files
            ]);
        }
    }
}
