<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'order_date' => $this->order_date,
            'delivery_user' => $this->delivery_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //order_users' => OrderUserResource::collection($this->whenLoaded('orderUsers')),
        ];
    }
}
