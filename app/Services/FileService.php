<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;
use Illuminate\Http\Request;

class FileService extends Controller
{
    public function getFiles($model, $id)
    {
        try {
            return response()->json([
                "status" => 200,
                "data" => PhotoResource::collection($model::with(["files", "files.owner"])->findOrFail($id)->files)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function saveFile(Request $rq, $model, $id, $dir)
    {
        try {


            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, $dir);

                // Save photo metadata in the database
                $model::findOrFail($id)->files()->createMany($_files);
            }

            return response()->json([
                "status" => 201,
                "message" => "File uploaded successfully"
            ]);


            // Validate the request
            $rq->validate([
                'photo' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Adjust the mimes and max size as needed
            ]);

            $file = $rq->file('photo');

            // Get the file extension
            $extension = $file->getClientOriginalExtension();

            // Generate a unique name for the file
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            // Define the file path
            $filePath = "uploads/photos";

            // Move the file to the destination path
            $file->move(public_path($filePath), $fileName);

            // Save photo metadata in the database
            $model::findOrFail($id)->photos()->create([
                'name' => $fileName,
                'type' => $extension,
                'path' => "{$filePath}/{$fileName}",
                'owner_id' => auth()->user()->id
            ]);

            return response()->json([
                "status" => 201,
                "message" => "Photo uploaded successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th->getMessage()
            ]);
        }
    }
}
