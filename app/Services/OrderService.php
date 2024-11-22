<?php

namespace App\Services;

use App\Models\Order;

use Exception;


class OrderService
{
    public function createDraftOrder(array $data): Order
    {
        return Order::create([
            'reason' => $data['reason'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
            'state' => Order::STATE_DRAFT,
        ]);
    }

    public function updateOrder(array $data, int $id): Order
    {
        $order = Order::findOrFail($id);

        //  dd($order->state);

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('Cannot update order, it is not in a valid state for modification.');
        }

        $order->update([
            'reason' => $data['reason'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
        ]);

        // Cambiar el estado si viene explícito en los datos y es válido
        if (isset($data['state']) && $order->isTransitionValid($data['state'])) {
            $order->update(['state' => $data['state']]);
        } else {
            throw new Exception("Invalid state transition.");
        }

        return $order;
    }
}
