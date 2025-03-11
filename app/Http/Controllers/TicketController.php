<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class TicketController extends Controller
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
            return (new DatatableService())->create(Ticket::with(["owner", "category"])->withCount("comments"), TicketResource::class);
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

            return new TicketResource(
                Ticket::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(TicketRequest $rq)
    {
        try {

            $t = Ticket::find($rq->id);
            $t->update($rq->validated());


            activity()
                ->performedOn($t)
                ->log(':causer.name modified ticket (:subject.subject)');

            return response()->json([
                "status" => 201,
                "message" => "Ticket updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(TicketRequest $rq)
    {
        try {

            $ticket = Ticket::create($rq->validated());


            activity()
                ->performedOn($ticket)
                ->log(':causer.name created ticket (:subject.subject)');

            if (!$rq->hasFile('files')) {
                return response()->json([
                    "status" => 201,
                    "message" => "Ticket created successfully, early"
                ]);
            }

            $files = $rq->file('files');

            $_files = $this->handleFileUpload($files, "Tickets");



            $ticket->files()->createMany($_files);

            // $this->fileService->saveFile($rq, Ticket::class, $ticket->id);

            return response()->json([
                "status" => 201,
                "message" => "Ticket created successfully"
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

            $ticket = Ticket::with("files")->find($rq->id);
            $files = $ticket->files->pluck("path")->toArray();

            $ticket->files()->delete();

            foreach ($files as $file) {
                $this->handleFileDeletion($file);
            }

            activity()
                ->performedOn($ticket)
                ->log(':causer.name deleted ticket (:subject.subject)');

            $ticket->delete();

            return response()->json([
                "status" => 201,
                "message" => "Ticket deleted successfully"
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

            $model = Ticket::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' ticket (:subject.subject)');

            return response()->json([
                "status" => 201,
                "message" => "Ticket {$op} successfully"
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

            $model = Ticket::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed ticket (:subject.subject) status');

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
        return $this->commentService->getComments(Ticket::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        $t = Ticket::find($rq->id);


        activity()
            ->performedOn($t)
            ->log(':causer.name commented on ticket (:subject.subject)');

        return $this->commentService->saveComment($rq, Ticket::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Ticket::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        $t = Ticket::find($rq->id);


        activity()
            ->performedOn($t)
            ->log(':causer.name attached file to ticket (:subject.subject)');

        return $this->fileService->saveFile($rq, Ticket::class, $rq->id, "Ticket Files");
    }
}
