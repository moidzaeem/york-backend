<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "owner_name" => $this->when(!$request->has("all_attributes"), $this->owner->name),
            "category_name" => $this->when(!$request->has("all_attributes"), $this->category->title),
            "subject" => $this->subject,
            "status" => $this->status,
            "description" => $this->description,
            "created_at" => $this->created_at->format("d M Y h:i a"),
            "total_comments" => $this->comments_count,

            "owner_id" => $this->when($request->has("all_attributes"), $this->owner_id),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
        ];
    }
}
