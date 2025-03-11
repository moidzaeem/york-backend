<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Services\DatatableService;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Position::query(), PositionResource::class);
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

            return new PositionResource(
                Position::findOrFail($id)
            );
            
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(PositionRequest $rq)
    {
        try {
            
            Position::find($rq->id)->update($rq->validated());

            return response()->json([
                "status" => 201,
                "message" => "Position updated successfully"
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(PositionRequest $rq)
    {
        try {
            
            Position::create($rq->validated());

            return response()->json([
                "status" => 201,
                "message" => "Position created successfully"
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
            
            Position::destroy($rq->id);

            return response()->json([
                "status" => 201,
                "message" => "Position deleted successfully"
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
