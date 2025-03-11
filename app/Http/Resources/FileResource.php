<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            "slug" => $this->slug,
            "type" => $this->type,
            "mimetype" => $this->mimetype,
            "path" => url("file/" . $this->slug),
            "created_at" => $this->created_at->format("d M Y"),
            "updated_at" => $this->updated_at->format("d M Y")
        ];
    }
}
