<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->profile->id,
            "client_id" => $this->id,
            "client_name" => $this->profile->name,
            "company_name" => $this->company_name,
            "phone_gsm" => $this->phone_gsm,
            "phone_stable" => $this->phone_stable,
            "address" => $this->profile->address,
            "email" => $this->profile->email,
            "created_at" => $this->created_at->format("d M Y"),
            "avatar" => $this->profile->avatar ? public_path($this->profile->avatar) : "",
            "special" => $this->profile->special ? true : false,
            "blacklist" => $this->profile->blacklist ? true : false,
            "decorated" => $this->profile->blacklist ? "blacklist" : ($this->profile->special ? "special" : "_"),
            "status" => $this->profile->status,
        ];
    }
}
