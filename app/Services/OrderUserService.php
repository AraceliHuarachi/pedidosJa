<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use Illuminate\Http\JsonResponse;

class OrderUserService
{
    /**
     * Crear un registro de OrderUser.
     *
     * @param array $data Datos validados para la orden y el usuario.
     * @return OrderUser
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la orden no existe.
     * @throws \Exception Si la orden no está en un estado válido.
     */
    public function createOrderUser(array $data): OrderUser
    {
        // Verificar que la orden exista y esté en un estado válido
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
}
