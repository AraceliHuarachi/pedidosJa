<?php

namespace App\Services;

use App\Models\OrderUserProduct;
use App\Models\Order;
use App\Models\OrderUser;

class AmountValidationService
{
    public function ValidateAmountMoney(int $orderId, int $userId, ?float $amountMoney, string $state): void
    {
        if ($state === 'in_process') {

            //Verificamos que amount_money no sea null
            if ($amountMoney === null) {
                throw new \Exception("The amount_money field is required in the 'in_process' state.");
            }

            $orderUser = OrderUser::where('order_id', $orderId)->firstOrFail();

            $userId = $orderUser->user_id;

            //calculo de la sumatoria de final_price
            $totalFinalPrice = $this->getTotalFinalPriceForUser($orderId, $userId);

            //verificamos que sea mayor a la sumatoria de final_price
            if ($amountMoney < $totalFinalPrice) {
                throw new \Exception(
                    "The amount ($amountMoney) must be greater than or equal to the sum of the final prices ($totalFinalPrice)."
                );
            }
        }
    }

    //Obtiene la sumatoria de final_price para un usuario especifico.
    private function getTotalFinalPriceForUser(int $orderId, int $userId): float
    {
        $orderUser = OrderUser::where('order_id', $orderId)
            ->Where('user_id', $userId)
            ->firstOrFail();

        return $orderUser->orderUserProducts->sum('final_price');
    }
}
