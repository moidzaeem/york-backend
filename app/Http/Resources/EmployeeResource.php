<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $dob = "";

        if ($this->profile->dob) {
            $dob = $request->has("all_attributes") ? $this->profile->dob->format("Y-m-d") : $this->profile->dob->format("d M Y");
        }

        return [
            "id" => $this->id,
            "user_id" => $this->profile->id,
            "name" => $this->profile->name,

            "position" => $this->when(!$request->has("all_attributes"), $this->position->title),
            "department" => $this->when(!$request->has("all_attributes"), $this->department->name),

            "phone_number" => $this->profile->phone_number,
            "email" => $this->profile->email,
            "address" => $this->profile->address,
            "employment_type" => $this->employment_type,
            "avatar" => $this->profile->avatar ? url("file/" . $this->profile->avatar) : "",
            "dob" => $dob,

            "position_id" => $this->when($request->has("all_attributes"), $this->position_id),
            "department_id" => $this->when($request->has("all_attributes"), $this->department_id),
            "special" => $this->when($request->has("all_attributes"), $this->profile->special),
            "blacklist" => $this->when($request->has("all_attributes"), $this->profile->blacklist),
            "notes" => $this->notes,
            "role" => $this->when($request->has("all_attributes"), $this->profile->roles->count() ? $this->profile->roles->first()->name : ""),
            "permissions" => $this->when($request->has("all_attributes"), $this->profile->permissions->count() ? $this->profile->permissions->pluck('name')->toArray() : []),
            // "permissions" => [],
        ];
    }
}
