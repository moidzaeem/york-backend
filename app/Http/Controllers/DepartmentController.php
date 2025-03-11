<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function list(Request $rq)
    {
        try {
            return (new DatatableService())->create(Department::query(), DepartmentResource::class);
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

            return new DepartmentResource(
                Department::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(DepartmentRequest $rq)
    {
        try {

            $d = Department::find($rq->id);
            $d->update($rq->validated());


            activity()
                ->performedOn($d)
                ->log(':causer.name modified department (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Department updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(DepartmentRequest $rq)
    {
        try {

            $d = Department::create($rq->validated());


            activity()
                ->performedOn($d)
                ->log(':causer.name created department (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Department created successfully"
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

            $d = Department::find($rq->id);


            activity()
                ->performedOn($d)
                ->log(':causer.name deleted department (:subject.name)');

            $d->delete();

            return response()->json([
                "status" => 201,
                "message" => "Department deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
