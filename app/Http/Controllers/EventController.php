<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Illuminate\Http\Request;

class EventController extends Controller
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
            return (new DatatableService())->create(Event::with(["client", "assignee", "department"]), EventResource::class);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => $th?->getMessage()
            ]);
        }
    }

    public function monthlyEvents(Request $rq, $month)
    {

        try {

            if (env("APP_ENV") == "local") {
                return
                    Event::selectRaw('*, strftime("%d", due_date) as date')
                    ->whereYear('due_date', date('Y'))
                    ->whereMonth('due_date', $month)
                    ->orderBy('due_date', 'asc')
                    ->get()
                    ->map(function ($ev) {
                        $ev->formated_date = $ev->due_date->format("d M Y h:i a");
                        return $ev;
                    })
                    ->groupBy('date');
            } else {

                return Event::selectRaw('*, DAY(due_date) as date')
                    ->whereYear('due_date', date('Y'))
                    ->whereMonth('due_date', $month)
                    ->orderBy('due_date', 'asc')
                    ->get()
                    ->map(function ($ev) {
                        $ev->formated_date = $ev->due_date->format("d M Y h:i a");
                        return $ev;
                    })
                    ->groupBy('date');
            }


            // return EventResource::collection(
            //     Event::with(["client", "assignee", "department"])
            //         ->whereYear("due_date", date("Y"))
            //         ->whereMonth("due_date", $month)->orderBy("due_date", "asc")->get()
            // );
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

            return new EventResource(
                Event::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(EventRequest $rq)
    {
        try {

            $e = Event::find($rq->id);
            $e->update($rq->validated());


            activity()
                ->performedOn($e)
                ->log(':causer.name modified event (:subject.event_name)');

            return response()->json([
                "status" => 201,
                "message" => "Event updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(EventRequest $rq)
    {
        try {

            $e = Event::create($rq->validated());


            activity()
                ->performedOn($e)
                ->log(':causer.name created event (:subject.event_name)');

            return response()->json([
                "status" => 201,
                "message" => "Event created successfully"
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

            $e = Event::find($rq->id);


            activity()
                ->performedOn($e)
                ->log(':causer.name deleted event (:subject.event_name)');

            $e->delete();

            return response()->json([
                "status" => 201,
                "message" => "Event deleted successfully"
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

            $model = Event::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' event (:subject.event_name)');

            return response()->json([
                "status" => 201,
                "message" => "Event {$op} successfully"
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

            $model = Event::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed event (:subject.event_name) status');

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
        return $this->commentService->getComments(Event::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        $e = Event::find($rq->id);

        activity()
            ->performedOn($e)
            ->log(':causer.name commented on event (:subject.event_name)');
        return $this->commentService->saveComment($rq, Event::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Event::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        $e = Event::find($rq->id);

        activity()
            ->performedOn($e)
            ->log(':causer.name attached file to event (:subject.event_name)');

        return $this->fileService->saveFile($rq, Event::class, $rq->id, "events");
    }
}
