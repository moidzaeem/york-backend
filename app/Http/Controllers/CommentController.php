<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function deleteComment(Request $rq)
    {
        try {
            $c = Comment::destroy($rq->id);

            return response()->json([
                "status" => 201,
                "message" => "Comment deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
