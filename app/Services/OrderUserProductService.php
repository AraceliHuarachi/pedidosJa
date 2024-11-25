<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class OrderUserProductService
{
    public function createOrderUserProduct(array $data): OrderUserProduct
    {
        $orderUser = OrderUser::findOrFail($data['order_user_id']);
        $order = $orderUser->order;

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('The order is not in a valid state to add products.');
        }

        $orderUserProduct = OrderUserProduct::create([
            'order_user_id' => $data['order_user_id'],
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'description' => $data['description'],
            'final_price' => $data['final_price'],
        ]);

        return $orderUserProduct;
    }

    public function updateOrderUserProduct(array $data, int $id): OrderUserProduct
    {
        $orderUserProduct = OrderUserProduct::findOrFail($id);
        $orderUser = $orderUserProduct->orderUser;
        $order = $orderUser->order;

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('The order is not in a valid state to update.');
        }

        $orderUserProduct->update([
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'description' => $data['description'],
            'final_price' => $data['final_price'],
        ]);

        return $orderUserProduct;
    }

    public function deleteOrderUserProduct(int $id): void
    {
        $orderUserProduct = OrderUserProduct::findOrFail($id);
        $orderUser = $orderUserProduct->orderUser;
        $order = $orderUser->order;

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('The order is not in a valid state to delete products.');
        }
        $orderUserProduct->delete();
    }
}
