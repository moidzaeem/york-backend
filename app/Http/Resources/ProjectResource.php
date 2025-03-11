<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            "manager" => $this->when(!$request->has("all_attributes"), $this->manager->name),
            "category_name" => $this->when(!$request->has("all_attributes"), $this->category->title),

            "start_date" => $request->has("all_attributes") ? $this->start_date->format("Y-m-d") : $this->start_date->format("d M Y"),
            "end_date" => $request->has("all_attributes") ? $this->end_date->format("Y-m-d") : $this->end_date->format("d M Y"),
            "status" => $this->status,
            "total_comments" => $this->comments_count,

            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "manager_id" => $this->when($request->has("all_attributes"), $this->manager_id),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            "notes" => $this->notes,
        ];
    }
}
