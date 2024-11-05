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
    public function createFinalOrder() {}

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
