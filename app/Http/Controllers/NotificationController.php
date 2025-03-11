<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(Request $rq)
    {
        try {

            $notifications = auth()->user()->notifications->pluck("data")->toArray();
            // $notifications = User::find(3)->notifications->pluck("data")->toArray();

            return response()->json([
                // "data" => [
                //     [
                //         "id" => 1,
                //         "message" => "You have a new message"
                //     ],
                //     [
                //         "id" => 2,
                //         "message" => "Your order has been shipped"
                //     ],
                //     [
                //         "id" => 3,
                //         "message" => "New comment on your post"
                //     ],
                //     [
                //         "id" => 4,
                //         "message" => "New user registered"
                //     ]
                // ]
                "data" => $notifications
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th->getMessage()
            ]);
        }
    }
}
