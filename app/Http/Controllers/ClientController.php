<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Client::with(["profile"]), ClientResource::class);
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

            return new ClientResource(
                Client::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(ClientRequest $rq)
    {
        try {

            // Update User
            $user = User::find($rq->id);

            $user->update($rq->safe()->only([
                'name',
                'phone_number',
                'email',
                'address',
                'special',
                'blacklist',
                'status'
            ]));

            // Update Client
            $user->client()->update($rq->safe()->only([
                'company_name',
                'phone_gsm',
                'phone_stable',
                'status'
            ]));


            activity()
                ->performedOn($user)
                ->log(':causer.name modified client (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Client updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(ClientRequest $rq)
    {
        try {

            // Create User
            $user = User::create([
                ...$rq->safe()->only([
                    'name',
                    'phone_number',
                    'email',
                    'address',
                    'special',
                    'blacklist',
                    'status'
                ]),
                'password' => bcrypt($rq->input('password')),
                'is_client' => true,
            ]);

            // Create Client
            $user->client()->create($rq->safe()->only([
                'company_name',
                'phone_gsm',
                'phone_stable',
                'status'
            ]));


            activity()
                ->performedOn($user)
                ->log(':causer.name created client (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Client created successfully"
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

            $user = User::find($rq->id);
            $user->client()->delete();


            activity()
                ->performedOn($user)
                ->log(':causer.name deleted client (:subject.name)');

            $user->delete();

            return response()->json([
                "status" => 201,
                "message" => "Client deleted successfully"
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

            $model = Client::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' client (:subject.company_name)');

            return response()->json([
                "status" => 201,
                "message" => "Client {$op} successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
