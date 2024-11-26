<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderUserResource extends JsonResource
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
            'order_id' => $this->order_id,
            'user_id' => $this->whenLoaded('user') ? $this->user->id : null,
            'user_name' => $this->user_name,
            'amount_money' => $this->amount_money,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products' => OrderUserProductResource::collection($this->whenLoaded('orderUserProducts')),
        ];
    }
}
