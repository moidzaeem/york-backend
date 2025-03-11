<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $words = explode(" ", $this->owner->name);
        $name_initials = "";

        foreach ($words as $word) {
            $name_initials .= strtoupper($word[0]);
        }

        return [
            "id" => $this->id,
            "task_id" => $this->commentable_id,
            "comment" => $this->comment,
            "owner" => [
                "name" => $this->owner->name,
                "initial" => $name_initials,
                "avatar" => $this->owner->avatar ? asset("storage/" .$this->owner->avatar) : false,
            ],
            "isOwner" => $this->owner_id === auth()->user()->id,
            "time" => $this->created_at->format("h:i a"),
            "date" => $this->created_at->format("d M Y"),
        ];
    }
}
