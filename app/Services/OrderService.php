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

        //Si la orden esta en estado "in_process" y se esta actualizando el monto, aplicamos la validacion
        if ($order->state === Order::STATE_IN_PROCESS) {
            $this->amountValidationService->ValidateAmountMoney(
                $order->id,
                $data['user_id'],
                $data['amount_money'],
                $order->state
            );
        }

        $order->update([
            'reason' => $data['reason'] ?? $order->reason,
            'delivery_user_id' => $data['delivery_user_id'] ?? $order->delivery_user_id,
            'd_user_name' => $data['d_user_name'] ?? $order->d_user_name,
            'order_date' => $data['order_date'] ?? $order->order_date,
        ]);

        // Cambiar el estado si viene explícito en los datos y es válido
        if (isset($data['state'])) {
            if ($order->isTransitionValid($data['state'])) {
                $order->update(['state' => $data['state']]);
            } else {
                throw new Exception("Invalid state transition.");
            }
        }

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
