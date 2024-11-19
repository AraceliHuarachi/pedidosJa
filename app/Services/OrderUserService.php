<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use Exception;
use Illuminate\Http\JsonResponse;

class OrderUserService
{
    public function createOrderUser(array $data): OrderUser
    {
        $order = Order::findOrFail($data['order_id']);
        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new \Exception('The order is not in a valid state to add users.');
        }

        return OrderUser::create([
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'amount_money' => null,
        ]);
    }
    public function updateOrderUser(array $data, int $id): OrderUser
    {
        $orderUser = OrderUser::findOrFail($id);

        $order = $orderUser->order;
        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('The order is not in a valid state to update users.');
        }

        $orderUser->update([
            'user_id' => $data['user_id'],
            'amount_money' => $data['amount_money'] ?? $orderUser->amount_money,
        ]);

        return $orderUser;
    }
}
