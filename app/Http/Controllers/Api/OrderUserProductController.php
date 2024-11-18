<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUserProduct;
use App\Http\Requests\OrderUserProductRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderUser;

class OrderUserProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderUserProductRequest $request)
    {
        $validated = $request->validated();

        // Obtener el OrderUser y verificar que la orden esté en estado válido
        $orderUser = OrderUser::findOrFail($validated['order_user_id']);
        $order = $orderUser->order;

        if ($order->state !== Order::STATE_IN_PROCESS) {
            return response()->json(['error' => 'La orden no está en un estado válido para asociar productos'], 400);
        }

        // Crear el registro en order_user_products
        $orderUserProduct = OrderUserProduct::create([
            'order_user_id' => $validated['order_user_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'amount_money' => $validated['amount_money'],
        ]);

        // Actualizar el campo amount_money en order_user
        $orderUser->update(['amount_money' => $validated['amount_money']]);

        // Retornar mensaje de éxito
        return response()->json(['message' => 'Productos asociados correctamente'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUserProductRequest $request, OrderUserProduct $orderUserProduct): OrderUserProduct
    {
        $orderUserProduct->update($request->validated());

        return $orderUserProduct;
    }

    public function destroy(OrderUserProduct $orderUserProduct): Response
    {
        $orderUserProduct->delete();

        return response()->noContent();
    }
}
