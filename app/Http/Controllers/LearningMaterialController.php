<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningMaterialRequest;
use App\Http\Resources\LearningMaterialResource;
use App\Models\LearningMaterial;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class LearningMaterialController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(LearningMaterial::with(["category", "attachedResource"]), LearningMaterialResource::class);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function edit($id)
    {
        try {

            request()->merge(["all_attributes" => true]);

            return new LearningMaterialResource(
                LearningMaterial::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(LearningMaterialRequest $rq)
    {
        try {

            $lm = LearningMaterial::find($rq->id);
            $path = $lm->path;

            if ($rq->hasFile("files")) {
                $this->handleFileDeletion($lm->path);

                $files = $rq->file('files');

                $path = $this->handleFileUpload($files, "Learning Materials", false);
            }

            $lm->update([
                ...$rq->validated(),
                "path" => $path
            ]);


            activity()
                ->performedOn($lm)
                ->log(':causer.name modified learning material (:subject.title)');

            return response()->json([
                "status" => 201,
                "message" => "Learning Material updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(LearningMaterialRequest $rq)
    {
        try {

            $files = $rq->file('files');

            $path = $this->handleFileUpload($files, "Learning Materials", false);

            $lm = LearningMaterial::create([
                ...$rq->validated(),
                "path" => $path,
            ]);


            activity()
                ->performedOn($lm)
                ->log(':causer.name created learning material (:subject.title)');

            return response()->json([
                "status" => 201,
                "message" => "Learning Material created successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function delete(Request $rq)
    {
        try {

            $lm = LearningMaterial::find($rq->id);
            $this->handleFileDeletion($lm->path);


            activity()
                ->performedOn($lm)
                ->log(':causer.name deleted learning material (:subject.title)');

            $lm->delete();

            return response()->json([
                "status" => 201,
                "message" => "LearningMaterial deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
