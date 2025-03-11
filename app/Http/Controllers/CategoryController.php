<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Category::query(), CategoryResource::class);
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

            return new CategoryResource(
                Category::findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(CategoryRequest $rq)
    {
        try {

            $c = Category::find($rq->id);
            $c->update($rq->validated());


            activity()
                ->performedOn($c)
                ->log(':causer.name modified category (:subject.title)');

            return response()->json([
                "status" => 201,
                "message" => "Category updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(CategoryRequest $rq)
    {
        try {

            $c = Category::create($rq->validated());


            activity()
                ->performedOn($c)
                ->log(':causer.name created category (:subject.title)');

            return response()->json([
                "status" => 201,
                "message" => "Category created successfully"
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

            $c = Category::find($rq->id);


            activity()
                ->performedOn($c)
                ->log(':causer.name deleted category (:subject.title)');

            $c->delete();

            return response()->json([
                "status" => 201,
                "message" => "Category deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
