<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PunchListResource extends JsonResource
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

            // "client_name" => $this->when(!$request->has("all_attributes"), $this->client->name),
            "client_name" => $this->when(!$request->has("all_attributes"), $this->project->client->name),
            "project_name" => $this->when(!$request->has("all_attributes"), $this->project->project_name),
            "assignee_name" => $this->when(!$request->has("all_attributes"), $this->assignee->name),

            "address" => $this->address,
            "date" => $request->has("all_attributes") ? $this->end_date->format("Y-m-d") : $this->end_date->format("d M Y"),
            "priority" => $this->priority,
            "status" => $this->status,
            "total_comments" => $this->comments_count,

            // "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "project_id" => $this->when($request->has("all_attributes"), $this->project_id),
            "assigned_to" => $this->when($request->has("all_attributes"), $this->assigned_to),
            "notes" => $this->notes
        ];
    }
}
