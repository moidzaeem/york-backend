<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $words = explode(" ", $this->name);
        $name_initials = "";

        foreach ($words as $word) {
            $name_initials .= strtoupper($word[0]);
        }

        $first_name = $words[0];

        return [
            "id" => $this->id,
            "name" => $this->name,
            "first_name" => $first_name,
            "name_initials" => $name_initials,
            "phone_number" => $this->phone_number,
            "created_at" => $this->created_at->format("d M Y"),
            "email" => $this->email,
            "dob" => $this->dob->format("M d"),
            "is_today" => $this->dob->format("M d") === now()->format("M d")
            // "role_id" => $this->roles->count() ? $this->roles->first()->id :  0,
            // "role_name" => $this->roles->count() ? $this->roles->first()->name : "",
        ];
    }
}
