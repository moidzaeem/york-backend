<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            "event_name" => $this->event_name,

            "assigned_to" => $this->when(!$request->has("all_attributes"), $this->assignee->name),
            "client_name" => $this->when(!$request->has("all_attributes"), $this->client->name),
            "department_name" => $this->when(!$request->has("all_attributes"), $this->department->name),

            "due_date" => $request->has("all_attributes") ? $this->due_date->format("Y-m-d H:i") : $this->due_date->format("d M Y h:i a"),
            "date" => $this->due_date->format("d"),
            // "priority" => $this->priority,
            "status" => $this->status,

            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "department_id" => $this->when($request->has("all_attributes"), $this->department_id),
            "assigned_to" => $this->when($request->has("all_attributes"), $this->assigned_to),
            "notes" => $this->notes
        ];
    }
}
