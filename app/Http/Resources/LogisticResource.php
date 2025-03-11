<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogisticResource extends JsonResource
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

            "client_name" => $this->when(!$request->has("all_attributes"), $this->client->name),
            "project" => $this->when(!$request->has("all_attributes"), $this->project->project_name),
            "project_type" => $this->when(!$request->has("all_attributes"), $this->project->category->title),
            "manager" => $this->when(!$request->has("all_attributes"), $this->manager->name),
            "assignee_name" => $this->when(!$request->has("all_attributes"), $this->assignee->name),

            "registration_date" => $request->has("all_attributes") ? $this->registration_date->format("Y-m-d") :  $this->registration_date->format("d M Y"),
            "shipment_date" => $request->has("all_attributes") ? $this->shipment_date->format("Y-m-d") :  $this->shipment_date->format("d M Y"),
            "status" => $this->status,
            "total_comments" => $this->comments_count,


            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "project_id" => $this->when($request->has("all_attributes"), $this->project_id),
            "manager_id" => $this->when($request->has("all_attributes"), $this->manager_id),
            "assigned_to" => $this->when($request->has("all_attributes"), $this->assigned_to),
            "notes" => $this->notes
        ];
    }
}
