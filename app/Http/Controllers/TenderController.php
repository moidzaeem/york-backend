<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenderRequest;
use App\Http\Resources\TenderResource;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TenderController extends Controller
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
            return (new DatatableService())->create(Tender::with(["client", "category"])->withCount("comments"), TenderResource::class);
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

            return new TenderResource(
                Tender::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(TenderRequest $rq)
    {
        try {

            $tender = Tender::find($rq->id)->update($rq->validated());


            activity()
                ->performedOn($tender)
                ->log(':causer.name modified tender');

            // $users = User::role(['CEO', 'Admin'])->get();

            // Notification::send($users, new GeneralNotification($tender, "updated", "tender"));

            return response()->json([
                "status" => 201,
                "message" => "Tender updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(TenderRequest $rq)
    {
        try {

            $tender = Tender::create($rq->validated());


            activity()
                ->performedOn($tender)
                ->log(':causer.name created tender');

            // $users = User::role(['CEO', 'Admin'])->get();

            // Notification::send($users, new GeneralNotification($tender, "created", "tender"));

            return response()->json([
                "status" => 201,
                "message" => "Tender created successfully"
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

            $tender = Tender::find($rq->id);

            activity()
                ->performedOn($tender)
                ->log(':causer.name deleted tender');

            $tender->delete();

            // $users = User::role(['CEO', 'Admin'])->get();

            // Notification::send($users, new GeneralNotification($tender, "deleted", "tender"));

            return response()->json([
                "status" => 201,
                "message" => "Tender deleted successfully"
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

            $model = Tender::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' tender');

            // $users = User::role(['CEO', 'Admin'])->get();

            // Notification::send($users, new GeneralNotification($model, strtolower($op), "tender"));

            return response()->json([
                "status" => 201,
                "message" => "Tender {$op} successfully"
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

            $model = Tender::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed tender status');

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
        return $this->commentService->getComments(Tender::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {

        activity()
            ->performedOn(new Tender())
            ->log(':causer.name commented on tender');

        return $this->commentService->saveComment($rq, Tender::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Tender::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        activity()
            ->performedOn(new Tender())
            ->log(':causer.name attached file to tender');

        return $this->fileService->saveFile($rq, Tender::class, $rq->id, "Tender Files");
    }
}
