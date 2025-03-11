<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class SaleController extends Controller
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
            return (new DatatableService())->create(Sale::with(["client", "salesPerson", "category"])->withCount("comments"), SaleResource::class);
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

            return new SaleResource(
                Sale::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(SaleRequest $rq)
    {
        try {

            $s = Sale::find($rq->id);

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Sales Files");

                $s->files()->createMany($_files);
            }

            $s->update($rq->validated());


            activity()
                ->performedOn($s)
                ->log(':causer.name modified sales');

            return response()->json([
                "status" => 201,
                "message" => "Sale updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(SaleRequest $rq)
    {
        try {

            $s = Sale::create($rq->validated());


            activity()
                ->performedOn($s)
                ->log(':causer.name created sales');

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Sales Files");

                $s->files()->createMany($_files);
            }

            return response()->json([
                "status" => 201,
                "message" => "Sale created successfully"
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

            $s = Sale::with("files")->find($rq->id);

            $files = $s->files->pluck("path")->toArray();

            $s->files()->delete();

            foreach ($files as $file) {
                $this->handleFileDeletion($file);
            }


            activity()
                ->performedOn($s)
                ->log(':causer.name deleted sales');

            $s->delete();

            return response()->json([
                "status" => 201,
                "message" => "Sale deleted successfully"
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

            $model = Sale::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' sales');

            return response()->json([
                "status" => 201,
                "message" => "Sale {$op} successfully"
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

            $model = Sale::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed sales status to ' . $rq->status);

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
        return $this->commentService->getComments(Sale::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {

        activity()
            ->performedOn(new Sale())
            ->log(':causer.name commented on sales');

        return $this->commentService->saveComment($rq, Sale::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Sale::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {

        activity()
            ->performedOn(new Sale())
            ->log(':causer.name attached file to sales');

        return $this->fileService->saveFile($rq, Sale::class, $rq->id, "Sales Files");
    }
}
