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
            'id' => $this->id, // ID del order_user
            'user' => new UserResource($this->whenLoaded('user')), // Información del usuario relacionado
            'amount_money' => $this->amount_money, // Dinero entregado en efectivo
            'order_id' => $this->order_id, // ID de la orden relacionada
            'created_at' => $this->created_at, // Fecha de creación
            'updated_at' => $this->updated_at, // Fecha de actualización
            'products' => OrderUserProductResource::collection($this->whenLoaded('products')), // Relación con los productos
        ];
    }
}
