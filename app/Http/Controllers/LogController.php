<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Services\DatatableService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Activity::query(), LogResource::class);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function delete(Request $rq)
    {
        try {

            Activity::destroy($rq->id);

            return response()->json([
                "status" => 201,
                "message" => "Activity deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
