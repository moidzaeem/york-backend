<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Mail\TaskAssigned;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Services\CommentService;
use App\Services\DatatableService;
use App\Services\FileService;
use Mail;

class TaskController extends Controller
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
            return (new DatatableService())->create(Task::with(["users"])->withCount("comments"), TaskResource::class);
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

            return new TaskResource(
                Task::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(TaskRequest $rq)
    {
        try {

            $t = Task::find($rq->id);
            $t->update($rq->validated());


            activity()
                ->performedOn($t)
                ->log(':causer.name modified task (:subject.title)');

            User::find($t->assigned_to)->notify(new GeneralNotification($t, "Your task has been updated"));

            return response()->json([
                "status" => 201,
                "message" => "Task updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(TaskRequest $rq)
    {
        try {
            // Validate the request data
            $validatedData = $rq->validated();
    
            // Create the task without 'assigned_to' (assuming it's handled separately)
            $t = Task::create($rq->except(['assigned_to']));
    
            // Check if 'assigned_to' exists and is an array (many-to-many relationship)
            if (isset($validatedData['assigned_to']) && is_array($validatedData['assigned_to'])) {
                // Attach the assigned users (many-to-many relationship)
                $assignedUsers = $validatedData['assigned_to'];
    
                // Ensure the users exist before attaching
                $validUsers = User::whereIn('id', $assignedUsers)->pluck('id')->toArray();
                if (!empty($validUsers)) {
                    $t->users()->attach($validUsers);
                }

                foreach ($validUsers as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        // Send email to each assigned user
                        Mail::to($user->email)->send(new TaskAssigned($t));
                    }
                }

            }
    
            // Log the task creation action
            activity()
                ->performedOn($t)
                ->log(':causer.name created task (:subject.title)');
    
            // Send notifications to all assigned users
            if (isset($assignedUsers) && !empty($assignedUsers)) {
                $users = User::whereIn('id', $assignedUsers)->get();
                foreach ($users as $user) {
                    $user->notify(new GeneralNotification($t, "You have a new task"));
                }
            }
    
            return response()->json([
                "status" => 201,
                "message" => "Task created and assigned successfully"
            ]);
        } catch (\Throwable $th) {
            // Log error and provide meaningful message in response
            \Log::error("Task creation failed: " . $th->getMessage());
            return response()->json([
                "status" => 500,  // Use 500 for internal server error
                "message" => "Something went wrong, please try again later."
            ]);
        }
    }
    
    


    public function delete(Request $rq)
    {
        try {

            $t = Task::find($rq->id);

            activity()
                ->performedOn($t)
                ->log(':causer.name deleted task (:subject.title)');

            $t->delete();

            return response()->json([
                "status" => 201,
                "message" => "Task deleted successfully"
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

            $model = Task::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' task (:subject.title)');

            return response()->json([
                "status" => 201,
                "message" => "Task {$op} successfully"
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

            $model = Task::find($rq->id);
            $model->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed task (:subject.title) status');

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
        return $this->commentService->getComments(Task::class, $rq->id);
    }


    public function saveComment(Request $rq)
    {
        $t = Task::find($rq->id);

        activity()
            ->performedOn($t)
            ->log(':causer.name commented on task (:subject.title)');

        return $this->commentService->saveComment($rq, Task::class, $rq->id);
    }

    public function files(Request $rq)
    {
        return $this->fileService->getFiles(Task::class, $rq->id);
    }


    public function saveFile(Request $rq)
    {
        $t = Task::find($rq->id);

        activity()
            ->performedOn($t)
            ->log(':causer.name attached file to task (:subject.title)');

        return $this->fileService->saveFile($rq, Task::class, $rq->id, "Task Files");
    }
}
