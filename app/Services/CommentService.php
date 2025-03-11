<?php

namespace App\Services;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentService
{
    public function getComments($model, $id)
    {
        try {
            // $comments = $model::with("comments")->findOrFail($id)->comments->toArray();

            $comments = $model::with("comments", "comments.owner")->findOrFail($id)->comments->sortByDesc("id");

            return response()->json([
                "status" => 200,
                "data" => CommentResource::collection($comments)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th->getMessage()
            ]);
        }
    }

    public function saveComment(Request $rq, $model, $id)
    {
        try {
            // Add your validation rules here if needed
            $rq->validate([
                'comment' => 'required|string|max:10000', // Example validation rule
            ]);

            $model::findOrFail($id)->comments()->create([
                'comment' => $rq->input('comment'),
                'owner_id' => auth()->user()->id, // Assuming you have a user_id field
            ]);

            return response()->json([
                "status" => 201,
                "message" => "Comment created successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th->getMessage()
            ]);
        }
    }
}
