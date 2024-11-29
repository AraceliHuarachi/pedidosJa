<?php

namespace App\Services;

use App\Models\Order;

use Exception;


class OrderService
{
    protected AmountValidationService $amountValidationService;

    /**
     * Inyectar AmountValidationService en el constructor.
     *
     * @param AmountValidationService $amountValidationService
     */
    public function __construct(AmountValidationService $amountValidationService)
    {
        $this->amountValidationService = $amountValidationService;
    }

    public function createDraftOrder(array $data): Order
    {
        return Order::create([
            'reason' => $data['reason'],
            'delivery_user_id' => $data['delivery_user_id'],
            'd_user_name' => $data['d_user_name'],
            'order_date' => $data['order_date'],
            'state' => Order::STATE_DRAFT,
        ]);
    }

    /**
     * Actualizar una orden.
     *
     * @param array $data
     * @param integer $id
     * @return Order
     */
    public function updateOrder(array $data, int $id): Order
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('Cannot update order, it is not in a valid state for modification.');
        }

        // Si el estado se está actualizando, validamos la transición
        if (isset($data['state'])) {
            $this->changeOrderState($order, $data['state']);
        }

        // Validación de monto si está en proceso
        if ($order->state === Order::STATE_IN_PROCESS) {

            $orderUser = $order->orderUser;

            $this->amountValidationService->ValidateAmountMoney(
                $order->id,
                $orderUser->user_id,
                $orderUser->amount_money ?? 0.0,
                $order->state
            );
        }

        // Actualización de los demás campos
        $order->update([
            'reason' => $data['reason'] ?? $order->reason,
            'delivery_user_id' => $data['delivery_user_id'] ?? $order->delivery_user_id,
            'd_user_name' => $data['d_user_name'] ?? $order->d_user_name,
            'order_date' => $data['order_date'] ?? $order->order_date,
        ]);

        return $order;
    }

    /**
     * Eliminar una orden.
     */
    public function deleteOrder(int $id): void
    {
        $order = Order::findOrFail($id);
        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            throw new Exception('Cannot delete order, it is not in a valid state.');
        }
        $order->delete();
    }


    /**
     * Cambiar el estado de una orden.
     */
    public function changeOrderState(Order $order, string $newState): void
    {
        $validTransitions = [
            Order::STATE_DRAFT => [Order::STATE_IN_PROCESS, Order::STATE_CANCELED],
            Order::STATE_IN_PROCESS => [Order::STATE_COMPLETED, Order::STATE_CANCELED],
            Order::STATE_COMPLETED => [],
            Order::STATE_CANCELED => [],
        ];

        if (!in_array($newState, $validTransitions[$order->state] ?? [])) {
            throw new Exception("Invalid state transition.");
        }
        $order->update(['state' => $newState]);
    }
}
