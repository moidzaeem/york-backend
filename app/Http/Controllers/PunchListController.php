<?php

namespace App\Http\Controllers;

use App\Http\Requests\PunchListRequest;
use App\Http\Resources\PunchListResource;
use App\Models\PunchList;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class PunchListController extends Controller
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
            return (new DatatableService())->create(PunchList::with(["project", "project.client", "assignee"])->withCount("comments"), PunchListResource::class);
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

            return new PunchListResource(
                PunchList::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(PunchListRequest $rq)
    {
        try {

            $p = PunchList::find($rq->id);

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Punch Lists");

                $p->photos()->createMany($_files);
            }

            $p->update($rq->validated());


            activity()
                ->performedOn($p)
                ->log(':causer.name modified punch list');

            return response()->json([
                "status" => 201,
                "message" => "PunchList updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(PunchListRequest $rq)
    {
        try {

            $p = PunchList::create($rq->validated());

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Punch Lists");

                $p->files()->createMany($_files);
            }


            activity()
                ->performedOn($p)
                ->log(':causer.name created punch list');

            return response()->json([
                "status" => 201,
                "message" => "PunchList created successfully"
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

            $p = PunchList::with("files")->find($rq->id);
            $files = $p->files->pluck("path")->toArray();

            $p->files()->delete();

            foreach ($files as $file) {
                $this->handleFileDeletion($file);
            }


            activity()
                ->performedOn($p)
                ->log(':causer.name deleted punch list');

            $p->delete();

            return response()->json([
                "status" => 201,
                "message" => "PunchList deleted successfully"
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

            $model = PunchList::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' punch list');

            return response()->json([
                "status" => 201,
                "message" => "Punch List {$op} successfully"
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

            $model = PunchList::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed punch list status to ' . $rq->status);

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
        return $this->commentService->getComments(PunchList::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {

        activity()
            ->performedOn(new PunchList())
            ->log(':causer.name commented on punch list');

        return $this->commentService->saveComment($rq, PunchList::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(PunchList::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {

        activity()
            ->performedOn(new PunchList())
            ->log(':causer.name attached file to punch list');

        return $this->fileService->saveFile($rq, PunchList::class, $rq->id, "Punch List Files");
    }
}
