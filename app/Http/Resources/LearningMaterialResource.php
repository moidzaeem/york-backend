<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningMaterialResource extends JsonResource
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
            "url" => $this->attachedResource?->slug ? url("api/file/" . $this->attachedResource->slug) : "", // $this->path, 
            "slug" => $this->attachedResource->slug,
            "name" => $this->attachedResource->name,
            "category_name" => $this->when(!$request->has("all_attributes"), $this->category->title),
            "created_at" => $this->created_at->format("d M Y h:i a"),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
        ];
    }
}
