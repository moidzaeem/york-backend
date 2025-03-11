<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class ProjectController extends Controller
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
            return (new DatatableService())->create(Project::with(["client", "manager", "category"])->withCount("comments"), ProjectResource::class);
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

            return new ProjectResource(
                Project::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(ProjectRequest $rq)
    {
        try {

            $p = Project::find($rq->id);

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Project Files");

                $p->files()->createMany($_files);
            }

            $p->update($rq->validated());


            activity()
                ->performedOn($p)
                ->log(':causer.name modified Project (:subject.project_name)');

            return response()->json([
                "status" => 201,
                "message" => "Project updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(ProjectRequest $rq)
    {
        try {

            // return $rq->validated();
            $p = Project::create($rq->validated());

            if ($rq->hasFile("files")) {
                $files = $rq->file('files');

                $_files = $this->handleFileUpload($files, "Project Files");

                // return $_files;
                $p->files()->createMany($_files);
            }


            activity()
                ->performedOn($p)
                ->log(':causer.name created Project (:subject.project_name)');

            return response()->json([
                "status" => 201,
                "message" => "Project created successfully"
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

            $p = Project::with("files")->find($rq->id);
            $files = $p->files->pluck("path")->toArray();

            $p->files()->delete();

            foreach ($files as $file) {
                unlink(storage_path("app/" . $file));
            }

            activity()
                ->performedOn($p)
                ->log(':causer.name deleted Project (:subject.project_name)');

            $p->delete();

            return response()->json([
                "status" => 201,
                "message" => "Project deleted successfully"
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

            $model = Project::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' Project (:subject.project_name)');

            return response()->json([
                "status" => 201,
                "message" => "Project {$op} successfully"
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

            $model = Project::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed Project (:subject.project_name) status');

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
        return $this->commentService->getComments(Project::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        $project = Project::find($rq->id);

        activity()
            ->performedOn($project)
            ->log(':causer.name commented on project (:subject.project_name)');

        return $this->commentService->saveComment($rq, Project::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Project::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        $project = Project::find($rq->id);

        activity()
            ->performedOn($project)
            ->log(':causer.name attached file to project (:subject.project_name)');

        return $this->fileService->saveFile($rq, Project::class, $rq->id, "Project Files");
    }
}
