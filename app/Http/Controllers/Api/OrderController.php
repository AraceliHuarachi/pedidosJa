<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Orders", description="API endpoints for managing orders")
 */
class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get list of orders",
     *     tags={"Orders"},
     *    
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
                'message' => 'No orders registered.',
            ], 404);
        }
        return OrderResource::collection($orders);
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
    public function store(OrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $order = $this->orderService->createDraftOrder($validated);

        return response()->json(['order_id' => $order->id], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get details of a specific order",
     *     description="Retrieve the details of a specific order by its ID, including related user and products.",
     *     operationId="showOrder",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the order",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Order description"),
     *             @OA\Property(property="order_date", type="string", format="date-time", example="2024-11-07T12:00:00Z"),
     *             @OA\Property(property="delivery_user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="Javi")
     *             ),
     *             @OA\Property(property="order_users", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="amount_money", type="string", example="10.00"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="name", type="string", example="Javi")
     *                     ),
     *                     @OA\Property(property="orderUserProducts", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="product_id", type="integer", example=1),
     *                             @OA\Property(property="quantity", type="integer", example=2),
     *                             @OA\Property(property="description", type="string", example="Product description"),
     *                             @OA\Property(property="final_price", type="string", example="20.00")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-11T12:32:54Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-11T12:32:54Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve order details"),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function show(Order $order): OrderResource
    {
        $order->load('deliveryUser', 'orderUsers.user', 'orderUsers.orderUserProducts');

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
