<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDirectoryRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\FileResource;
use App\Models\FileSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DatatableService;
use Exception;
use Illuminate\Support\Facades\Auth;

class FileSystemController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function list(Request $rq)
    {

        try {

            $filter = $rq->input("filter");
            $directoryID = false;

            if (isset($filter['dirID']) && $filter['dirID']) {
                $directoryID = $filter['dirID'];
            }

            return (new DatatableService())->create(
                FileSystem::query()
                    ->when($directoryID, function ($query) use ($directoryID) {
                        $query->where("parent_id", $directoryID);
                    }, function ($query) {
                        $query->where("parent_id", null);
                    }),
                FileResource::class
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => $th?->getMessage()
            ]);
        }
    }

    public function createDirectory(CreateDirectoryRequest $request)
    {
        try {

            if (!Storage::disk("local")->exists($request->validated()['path'])) {

                Storage::disk("local")->makeDirectory($request->validated()['path']);

                FileSystem::create($request->validated());

                return response()->json([
                    "status" => 201,
                    "message" => "Directory created successfully"
                ]);
            } else {
                return response()->json([
                    "status" => 200,
                    "message" => "Directory already exists"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => "Failed to create directory."
            ]);
        }
    }


    public function uploadFile(UploadRequest $request)
    {

        try {

            FileSystem::insert($request->validated()['data']);

            return response()->json([
                "status" => 201,
                "message" => "File(s) uploaded successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => "Failed to upload file(s)"
            ]);
        }
    }


    public function delete(Request $rq)
    {
        try {
            $resource = FileSystem::find($rq->id);

            if ($resource->type == "directory") {

                if (in_array($resource->slug, ['tickets', 'punch-lists', 'learning-materials', 'project-files', 'avatars'])) {
                    throw new Exception("Other resource depends on this directory");
                }

                $nestedContents = FileSystem::where("path", "like", $resource->path . "%")->get();

                foreach ($nestedContents as $dir) {
                    if (Storage::disk('local')->exists($dir->path)) {
                        Storage::disk('local')->deleteDirectory($dir->path);
                    }

                    $dir->delete();
                }

                // return $nestedContents;
            } else {
                Storage::disk('local')->delete($resource->path);
                $resource->delete();
                // return $resource;
            }
            // User::destroy($rq->id);

            return response()->json([
                "status" => 201,
                "message" => "Resource deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function getFile(Request $request, $slug)
    {
        if (!Auth::check()) {
            abort(404);
        }

        $file = FileSystem::where('slug', $slug)->where('type', 'file')->first();

        if (!$file) {
            abort(404);
        }

        return response()->file(storage_path('app/' . $file->path));
    }
}
