<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrderRequest;
use App\Http\Resources\ChangeOrderResource;
use App\Models\ChangeOrder;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class ChangeOrderController extends Controller
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
            return (new DatatableService())->create(ChangeOrder::with(["project", "project.client"])->withCount("comments"), ChangeOrderResource::class);
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

            return new ChangeOrderResource(
                ChangeOrder::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(ChangeOrderRequest $rq)
    {
        try {

            $c = ChangeOrder::find($rq->id);
            $c->update($rq->validated());


            activity()
                ->performedOn($c)
                ->log(':causer.name modified change order');

            return response()->json([
                "status" => 201,
                "message" => "ChangeOrder updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(ChangeOrderRequest $rq)
    {
        try {

            $c = ChangeOrder::create($rq->validated());


            activity()
                ->performedOn($c)
                ->log(':causer.name created change order');

            return response()->json([
                "status" => 201,
                "message" => "ChangeOrder created successfully"
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

            $c = ChangeOrder::find($rq->id);


            activity()
                ->performedOn($c)
                ->log(':causer.name deleted change order');

            $c->delete();

            return response()->json([
                "status" => 201,
                "message" => "ChangeOrder deleted successfully"
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

            $model = ChangeOrder::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' change order');

            return response()->json([
                "status" => 201,
                "message" => "Change Order {$op} successfully"
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
        return $this->commentService->getComments(ChangeOrder::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        activity()
            ->performedOn(new ChangeOrder())
            ->log(':causer.name commented on change order');

        return $this->commentService->saveComment($rq, ChangeOrder::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(ChangeOrder::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        activity()
            ->performedOn(new ChangeOrder())
            ->log(':causer.name attached file to change order');

        return $this->fileService->saveFile($rq, ChangeOrder::class, $rq->id, "Change Order Files");
    }
}
