<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogisticRequest;
use App\Http\Resources\LogisticResource;
use App\Models\Logistic;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class LogisticController extends Controller
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
            return (new DatatableService())->create(Logistic::with(["client", "project", "project.category", "manager", "assignee"])->withCount("comments"), LogisticResource::class);
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

            return new LogisticResource(
                Logistic::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(LogisticRequest $rq)
    {
        try {

            $l = Logistic::find($rq->id);
            $l->update($rq->validated());


            activity()
                ->performedOn($l)
                ->log(':causer.name modified logistics');

            return response()->json([
                "status" => 201,
                "message" => "Logistic updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(LogisticRequest $rq)
    {
        try {

            $l = Logistic::create($rq->validated());


            activity()
                ->performedOn($l)
                ->log(':causer.name created logistics');

            return response()->json([
                "status" => 201,
                "message" => "Logistic created successfully"
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

            $l = Logistic::find($rq->id);

            activity()
                ->performedOn($l)
                ->log(':causer.name deleted logistics');

            $l->delete();

            return response()->json([
                "status" => 201,
                "message" => "Logistic deleted successfully"
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

            $model = Logistic::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' logistics');

            return response()->json([
                "status" => 201,
                "message" => "Logistic {$op} successfully"
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

            $model = Logistic::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed logistics status');

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
        return $this->commentService->getComments(Logistic::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {

        activity()
            ->performedOn(new Logistic())
            ->log(':causer.name commented on logistics');

        return $this->commentService->saveComment($rq, Logistic::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Logistic::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {

        activity()
            ->performedOn(new Logistic())
            ->log(':causer.name attached file to logistics');

        return $this->fileService->saveFile($rq, Logistic::class, $rq->id, "Logistics");
    }
}
