<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * recibir datos enviados de .
     */
    public function storeFinalOrder(OrderRequest $request)
    {
        // Crear la orden y asociar usuarios y productos en un solo paso
        $order = Order::create([
            'user_id' => $request->user_id[0], // Asumimos que el primer user_id es el encargado
            'order_date' => $request->order_date,
            'description' => $request->description,
        ]);

        // Asociar order_users y order_user_products usando la relación
        $order->orderUsers()->createMany(array_map(function ($userId, $amount) use ($order) {
            return [
                'order_id' => $order->id,
                'user_id' => $userId,
                'amount_money' => $amount,
            ];
        }, $request->user_id, $request->amount_money));

        $order->orderUserProducts()->createMany(array_map(function ($product) use ($order) {
            return [
                'order_user_id' => $order->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'final_price' => $product['final_price'],
            ];
        }, $request->products));

        return new OrderResource($order);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): Order
    {
        return Order::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Order
    {
        return $order;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): Order
    {
        $order->update($request->validated());

        return $order;
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
