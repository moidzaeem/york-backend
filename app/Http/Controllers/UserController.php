<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function loggedinUser(Request $rq)
    {
        try {

            return new UserResource(
                $rq->user()
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(User::with(["roles"])->where("is_client", false), UserResource::class);
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

            return new UserResource(
                User::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(UserRequest $rq)
    {
        try {

            User::find($rq->id)->update($rq->validated());

            return response()->json([
                "status" => 201,
                "message" => "User updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(UserRequest $rq)
    {
        try {

            User::create($rq->validated());

            return response()->json([
                "status" => 201,
                "message" => "User created successfully"
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
            // User::destroy($rq->id);

            return response()->json([
                "status" => 201,
                "message" => "User deleted successfully"
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

            $model = User::find($rq->id)->update(["status" => $rq->status]);


            activity()
                ->performedOn($model)
                ->log(':causer.name changed :subject.name status to ' . $rq->status);

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


    public function switchRole(Request $rq)
    {
        try {

            $u = User::find($rq->id);
            $u->syncRoles($rq->role);


            activity()
                ->performedOn($u)
                ->log(':causer.name changed :subject.name role to ' . $rq->role);

            return response()->json([
                "status" => 201,
                "message" => "Role swtiched to {$rq->role} successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
