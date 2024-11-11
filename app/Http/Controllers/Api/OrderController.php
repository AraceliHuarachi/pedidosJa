<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

/**
 * @OA\Tag(name="Orders", description="API endpoints for managing orders")
 */
class OrderController extends Controller
{
    protected $orderService;
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get list of orders",
     *     tags={"Orders"},
     *     security={{"bearer":{}}},
     *     description="Retrieve a list of all orders.",
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *         )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $orders = Order::select('id', 'description', 'order_date', 'delivery_user_id')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No hay órdenes registradas.',
            ], 404);
        }
        return OrderResource::collection($orders);
    }

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Post(
     *     path="/api/orders", 
     *     summary="Create a new order",
     *     description="Creates a new order and stores its details, utilizing the OrderService.",
     *     operationId="storeOrder",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create order",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to create order"),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function store(OrderRequest $request)
    {
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
        // @todo: VERIFICAR SI EXISTE
        $order->load('deliveryUser', 'orderUsers.orderUserProducts');

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
