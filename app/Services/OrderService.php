<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createDraftOrder(array $data): Order
    {
        return Order::create([
            'description' => $data['description'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
            'state' => Order::STATE_DRAFT,
        ]);
    }

    public function updateOrder(array $data, int $id): Order
    {
        $order = Order::findOrFail($id);

        // Verificar que la orden esté en un estado válido para actualización
        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('Cannot update order, it is not in a valid state for modification.');
        }

        $order->update([
            'description' => $data['description'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
        ]);

        return $order;
    }
}
