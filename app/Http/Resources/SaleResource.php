<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            "sales_person" => $this->when(!$request->has("all_attributes"), $this->salesPerson->name),
            "category_name" => $this->when(!$request->has("all_attributes"), $this->category->title),

            "offer_price" => $this->offer_price,
            "date" => $this->date->format("d M Y"),
            "status" => $this->status,
            "total_comments" => $this->comments_count,


            "client_id" => $this->when($request->has("all_attributes"), $this->client_id),
            "sales_person_id" => $this->when($request->has("all_attributes"), $this->sales_person_id),
            "category_id" => $this->when($request->has("all_attributes"), $this->category_id),
            "notes" => $this->notes
        ];
    }
}
