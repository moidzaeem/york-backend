<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackChargeRequest;
use App\Http\Resources\BackChargeResource;
use App\Models\BackCharge;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class BackChargeController extends Controller
{

    protected $fileService;
    protected $commentService;

    public function __construct(FileService $fileService, CommentService $commentService)
    {
        $this->fileService = $fileService;
        $this->commentService = $commentService;
    }

    public function list(Request $rq)
    {

        try {

            // return BackCharge::with(["project", "project.client"])->get();
            return (new DatatableService())->create(BackCharge::with(["project", "project.client"])->withCount("comments"), BackChargeResource::class);
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

            return new BackChargeResource(
                BackCharge::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(BackChargeRequest $rq)
    {
        try {

            $b = BackCharge::find($rq->id);
            $b->update($rq->validated());


            activity()
                ->performedOn($b)
                ->log(':causer.name modified back charge');

            return response()->json([
                "status" => 201,
                "message" => "BackCharge updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(BackChargeRequest $rq)
    {
        try {

            $b = BackCharge::create($rq->validated());


            activity()
                ->performedOn($b)
                ->log(':causer.name created back charge');

            return response()->json([
                "status" => 201,
                "message" => "BackCharge created successfully"
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

            $b = BackCharge::find($rq->id);

            activity()
                ->performedOn($b)
                ->log(':causer.name deleted back charge');

            $b->delete();

            return response()->json([
                "status" => 201,
                "message" => "BackCharge deleted successfully"
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

            $model = BackCharge::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' back charge');

            return response()->json([
                "status" => 201,
                "message" => "Back Charge {$op} successfully"
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
        return $this->commentService->getComments(BackCharge::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        activity()
            ->performedOn(new BackCharge())
            ->log(':causer.name commented on back charge');

        return $this->commentService->saveComment($rq, BackCharge::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(BackCharge::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        activity()
            ->performedOn(new BackCharge())
            ->log(':causer.name attached file to back charge');

        return $this->fileService->saveFile($rq, BackCharge::class, $rq->id, "Back Charge Files");
    }
}
