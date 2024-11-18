<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class OrderUserProductService
{
    /**
     * Crear un registro en order_user_products y actualizar el monto en order_user.
     *
     * @param array $data Datos validados para la operación.
     * @throws ModelNotFoundException Si el registro en order_users no existe.
     * @throws Exception Si la orden no está en un estado válido.
     * @return OrderUserProduct
     */
    public function createOrderUserProduct(array $data): OrderUserProduct
    {
        // Verificar que el registro en order_users exista
        $orderUser = OrderUser::findOrFail($data['order_user_id']);
        $order = $orderUser->order;

        // Validar que la orden esté en un estado válido
        if ($order->state !== Order::STATE_IN_PROCESS) {
            throw new Exception('The order is not in a valid state to associate products.');
        }

        // Crear el registro en order_user_products
        $orderUserProduct = OrderUserProduct::create([
            'order_user_id' => $data['order_user_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'amount_money' => $data['amount_money'],
        ]);

        // Actualizar el campo amount_money en order_user
        $orderUser->update(['amount_money' => $data['amount_money']]);

        return $orderUserProduct;
    }
}
