<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function storeFinalOrder(array $data)
    {
        DB::beginTransaction();

        try {
            // Crear el pedido en la tabla `orders`
            $orderData = $data['order'];
            $order = Order::create([
                'description' => $orderData['description'],
                'delivery_user_id' => $orderData['delivery_user_id'],
                'order_date' => $orderData['order_date'],
            ]);

            // Iterar sobre cada usuario en `order_users` y guardar en la tabla `order_users`
            foreach ($orderData['order_users'] as $orderUserData) {
                $orderUser = OrderUser::create([
                    'user_id' => $orderUserData['user_id'],
                    'amount_money' => $orderUserData['amount_money'],
                    'order_id' => $order->id,
                ]);

                // Guardar los productos para cada usuario en la tabla `order_user_products`
                foreach ($orderUserData['products'] as $productData) {
                    OrderUserProduct::create([
                        'order_user_id' => $orderUser->id,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                        'description' => $productData['description'] ?? null,
                        'final_price' => $productData['final_price'],
                    ]);
                }
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
