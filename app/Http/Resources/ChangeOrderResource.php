<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChangeOrderResource extends JsonResource
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
            "total_comments" => $this->comments_count,

            "date" => $request->has("all_attributes") ? $this->date->format("Y-m-d") : $this->date->format("d M Y h:i a"),

            // "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "project_id" => $this->when($request->has("all_attributes"), $this->project_id),
            "floor" => $this->when($request->has("all_attributes"), $this->floor),
            "unit_number" => $this->when($request->has("all_attributes"), $this->unit_number),
            "description_of_change" => $this->when($request->has("all_attributes"), $this->description_of_change),
            "description_of_change_approval" => $this->when($request->has("all_attributes"), $this->description_of_change_approval),
            "reason_for_change" => $this->when($request->has("all_attributes"), $this->reason_for_change),
            "reason_for_change_approval" => $this->when($request->has("all_attributes"), $this->reason_for_change_approval),
            "additional_notes" => $this->when($request->has("all_attributes"), $this->additional_notes),
            "fix_amount" => $this->when($request->has("all_attributes"), $this->fix_amount),
            "charge_amount" => $this->when($request->has("all_attributes"), $this->charge_amount),
            "york_signature" => $this->when($request->has("all_attributes"), $this->york_signature),
            "client_signature" => $this->when($request->has("all_attributes"), $this->client_signature)
        ];
    }
}
