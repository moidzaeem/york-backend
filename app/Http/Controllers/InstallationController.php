<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallationRequest;
use App\Http\Resources\InstallationResource;
use App\Models\Installation;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class InstallationController extends Controller
{

    protected $fileService;
    protected $commentService;

    public function __construct(FileService $FileService, CommentService $commentService)
    {
        $this->fileService = $FileService;
        $this->commentService = $commentService;
    }

    public function list(Request $rq)
    {

        try {
            // return (new DatatableService())->create(Installation::with(["client", "assignee", "category"]), InstallationResource::class);
            return (new DatatableService())->create(Installation::with(["project", "project.client", "project.category", "manager", "assignee"])->withCount("comments"), InstallationResource::class);
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

            return new InstallationResource(
                Installation::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(InstallationRequest $rq)
    {
        try {

            $i = Installation::find($rq->id);
            $i->update($rq->validated());


            activity()
                ->performedOn($i)
                ->log(':causer.name modified installation');

            return response()->json([
                "status" => 201,
                "message" => "Installation updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(InstallationRequest $rq)
    {
        try {

            $i = Installation::create($rq->validated());


            activity()
                ->performedOn($i)
                ->log(':causer.name created installation');

            return response()->json([
                "status" => 201,
                "message" => "Installation created successfully"
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

            $i = Installation::find($rq->id);


            activity()
                ->performedOn($i)
                ->log(':causer.name deleted installation');

            $i->delete();

            return response()->json([
                "status" => 201,
                "message" => "Installation deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function archive(Request $rq)
    {
        try {

            $model = Installation::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' installations');

            return response()->json([
                "status" => 201,
                "message" => "Installation {$op} successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function changeStatus(Request $rq)
    {
        try {

            $model = Installation::find($rq->id);
            $model->update(["status" => $rq->status]);

            activity()
                ->performedOn($model)
                ->log(':causer.name changed installations status to ' . $rq->status);

            return response()->json([
                "status" => 201,
                "message" => "Status changed to {$rq->status} successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }

    public function comments(Request $rq)
    {
        return $this->commentService->getComments(Installation::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        activity()
            ->performedOn(new Installation())
            ->log(':causer.name commented on installations');

        return $this->commentService->saveComment($rq, Installation::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Installation::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        activity()
            ->performedOn(new Installation())
            ->log(':causer.name attached file to installations');

        return $this->fileService->saveFile($rq, Installation::class, $rq->id, "Installation Files");
    }
}
