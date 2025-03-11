<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputRequest;
use App\Http\Resources\InputResource;
use App\Models\Input;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class InputController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Input::query(), InputResource::class);
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

            return new InputResource(
                Input::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(InputRequest $rq)
    {
        try {

            $i = Input::find($rq->id);
            $i->update($rq->validated());


            activity()
                ->performedOn($i)
                ->log(':causer.name modified input (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Input updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(InputRequest $rq)
    {
        try {

            $i = Input::create($rq->validated());


            activity()
                ->performedOn($i)
                ->log(':causer.name created input (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Input created successfully"
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

            $i = Input::find($rq->id);


            activity()
                ->performedOn($i)
                ->log(':causer.name deleted input (:subject.name)');

            $i->delete();

            return response()->json([
                "status" => 201,
                "message" => "Input deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
