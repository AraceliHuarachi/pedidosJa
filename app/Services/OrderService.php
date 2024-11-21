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
            'reason' => $data['reason'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
            'state' => Order::STATE_DRAFT,
        ]);
    }

    public function updateOrder(array $data, int $id): Order
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('Cannot update order, it is not in a valid state for modification.');
        }

        $order->update([
            'reason' => $data['reason'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
        ]);
        // Cambiar el estado si viene explícito en los datos y es válido
        if (isset($data['state'])) {
            $this->changeOrderState($order, $data['state']);
        }

        return $order;
    }
    private function changeOrderState(Order $order, string $newState): void
    {
        // Definir las transiciones de estado válidas
        $validTransitions = [
            Order::STATE_DRAFT => [Order::STATE_IN_PROCESS],
            Order::STATE_IN_PROCESS => [Order::STATE_COMPLETED],
        ];

        if (
            array_key_exists($order->state, $validTransitions) &&
            in_array($newState, $validTransitions[$order->state])
        ) {
            $order->update(['state' => $newState]);
        } else {
            throw new Exception("Invalid state transition from {$order->state} to {$newState}.");
        };
    }
}
