<?php

namespace App\Http\Controllers;

use App\Models\FileSystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Controller
{

    protected function handleFileUpload($files, $dirName, $multiple = true)
    { 
        $slug = Str::slug($dirName);
        $filePath = "uploads/" . $slug;
        $date = now();
        $directory = null;

        if ($slug != "avatars") {
            if (!Storage::disk('local')->exists($filePath)) {
                Storage::disk("local")->makeDirectory($filePath);
    
                $directory = FileSystem::create([
                    'name' => $dirName,
                    'slug' => $slug,
                    'type' => 'directory',
                    'path' => $filePath
                ]);
            } else {
                $directory = FileSystem::where('slug', $slug)->first();
            }
        }


        if ($multiple) {

            $_files = [];

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
                    'owner_id' => auth()->user()->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            return $_files;
        }

        $file = $files[0];

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        if ($slug != "avatars") {
            $file->storeAs($filePath, $fileName, 'local');
    
            $uploadedFile = FileSystem::create([
                'name' => $file->getClientOriginalName(),
                'slug' => Str::slug(explode(".", $file->getClientOriginalName())[0]) . "_" . uniqid(),
                'path' => $filePath . "/" . $fileName,
                'type' => 'file',
                'mimetype' => $file->getClientMimeType(),
                'parent_id' => $directory?->id ?? null,
                'owner_id' => auth()->user()->id,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            return $uploadedFile->path;
        } 
        else {
            $file->storeAs($filePath, $fileName, 'public');
            
            return $filePath . "/" . $fileName;
        } 

    }


    protected function handleFileDeletion($path)
    {
        $resource = FileSystem::where('path', $path)->first();
        // return $resource->path;
        Storage::disk('local')->delete($resource->path);
        $resource->delete();
    }
}
