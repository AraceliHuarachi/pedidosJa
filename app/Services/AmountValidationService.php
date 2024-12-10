<?php

namespace App\Services;

use App\Models\OrderUserProduct;
use App\Models\Order;
use App\Models\OrderUser;

class AmountValidationService
{
    public function ValidateAmountMoney(int $orderId, int $userId, ?float $amountMoney): void
    {
        //obtenemos el OrderUser correspondiente.
        $orderUser = ORderUser::where('order_id', $orderId)
            ->where('user_id', $userId)->firstOrFail();

        // obtenemos el estado de la orden.
        $state = $orderUser->order->state;

        if (in_array($state, ['draft', 'in_process'])) {

            //Verificamos que amount_money no sea nullo, cuando el estado sea in_process
            if ($state === 'in_process' && $amountMoney === null) {
                throw new \Exception("the ampunt_money field is required in the 'in_process' state.");
            }

            //sumatoria de final_price por quantity
            $totalFinalPrice = $this->getTotalFinalPriceForUser($orderId, $userId);

            //comparacion con amount_money
            if ($amountMoney < $totalFinalPrice) {
                throw new \Exception(
                    "The amount-money ($amountMoney) must be greater than or equal to the sum of the prices ($totalFinalPrice)."
                );
            }
        }
    }

    //Obtiene la sumatoria de final_price multiplicado por quantity para un usuario especifico.
    private function getTotalFinalPriceForUser(int $orderId, int $userId): float
    {
        //obtener el orderUser correspondiente.
        $orderUser = OrderUser::where('order_id', $orderId)
            ->Where('user_id', $userId)
            ->firstOrFail();

        return $orderUser->orderUserProducts->sum(function ($product) {
            return $product->final_price * $product->quantity; // Multiplicamos el final_price por la cantidad
        });
    }
}
