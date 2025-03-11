<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InputResource extends JsonResource
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
            "name" => $this->name,
            "code" => $this->code,
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            "category" => $this->when(!$request->has("all_attributes"), $this->category),
            "notes" => $this->notes,
            "created_at" => $this->created_at->format("d M Y h:i a"),
            "updated_at" => $this->updated_at->format("d M Y h:i a"),
        ];
    }
}
