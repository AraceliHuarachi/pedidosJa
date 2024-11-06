<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        // Llamar al método del servicio para crear la orden y sus detalles
        try {
            $this->orderService->storeFinalOrder($request->validated());
            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): OrderResource
    {
        $order->load('deliveryUser', 'orderUsers.products');

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->validated());

        $order->load('deliveryUser', 'orderUsers.products');

        return new OrderResource($order);
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
