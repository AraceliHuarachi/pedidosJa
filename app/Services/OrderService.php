<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Crear una nueva orden
     * 
     * @param array $data
     * @return Order
     */

    public function createDraftOrder(array $data): Order
    {
        return Order::create([
            'description' => $data['description'],
            'delivery_user_id' => $data['delivery_user_id'],
            'order_date' => $data['order_date'],
            'state' => Order::STATE_DRAFT,
        ]);
    }
}
