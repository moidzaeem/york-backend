<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodayTaskResource extends JsonResource
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
            "priority" => $this->priority,
            "status" => $this->status,
            "start_date" => $this->start_date->format("d M Y"),
            "end_date" => $this->end_date->format("d M Y"),
            "notes" => $this->notes,
        ];
    }
}
