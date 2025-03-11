<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenderResource extends JsonResource
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
            "project_name" => $this->project_name,

            "client_name" => $this->when(!$request->has("all_attributes"), $this->client->name),
            "category_name" => $this->when(!$request->has("all_attributes"), $this->category->title),

            "closing_date" => $request->has("all_attributes") ? $this->closing_date->format("Y-m-d") : $this->closing_date->format("d M Y"),
            "status" => $this->status,
            "total_comments" => $this->comments_count,

            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            "notes" => $this->notes
        ];
    }
}
