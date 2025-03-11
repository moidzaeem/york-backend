<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            "title" => $this->title,
            // If 'all_attributes' is not set, return the first assignee's name, else return all assigned users
            "assignee_name" => $this->when(
                !$request->has("all_attributes"),
                $this->users->isEmpty() ? 'No Assignee' : $this->users->pluck('name')->implode(', ')
            ),

            "priority" => $this->priority,
            "status" => $this->status,
            "total_comments" => $this->comments_count,

            // Formatting dates based on whether 'all_attributes' is present
            "start_date" => $request->has("all_attributes")
                ? $this->start_date->format("Y-m-d")
                : $this->start_date->format("d M Y"),

            "end_date" => $request->has("all_attributes")
                ? $this->end_date->format("Y-m-d")
                : $this->end_date->format("d M Y"),

            // Return more data when 'all_attributes' is present
            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "department_id" => $this->when($request->has("all_attributes"), $this->department_id),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            // If 'all_attributes' is set, return all assigned users (many-to-many)
            // "assigned_to" => $this->when(
            //     $request->has("all_attributes"), 
            //     $this->assignedUsers->pluck('name')  // Get names of all assigned users
            // ),
            "notes" => $this->notes,
        ];
    }
}
