<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallationResource extends JsonResource
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

            "client_name" => $this->when(!$request->has("all_attributes"), $this->project->client->name),
            "manager_name" => $this->when(!$request->has("all_attributes"), $this->manager->name),
            "assignee" => $this->when(!$request->has("all_attributes"), $this->assignee->name),
            "category_name" => $this->when(!$request->has("all_attributes"), $this->project->category->title),

            "installation_amount" => $this->installation_amount,
            "shipment_amount" => $this->shipment_amount,
            "start_date" => $request->has("all_attributes") ? $this->start_date->format("Y-m-d") : $this->start_date->format("d M Y"),
            "end_date" => $request->has("all_attributes") ? $this->end_date->format("Y-m-d") : $this->end_date->format("d M Y"),
            "status" => $this->status,
            "total_comments" => $this->comments_count,

            "project_id" => $this->when($request->has("all_attributes"), $this->project_id),
            "manager_id" => $this->when($request->has("all_attributes"), $this->manager_id),
            "assigned_to" => $this->when($request->has("all_attributes"), $this->assigned_to),
            // "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            "notes" => $this->when($request->has("all_attributes"), $this->notes),
        ];
    }
}
