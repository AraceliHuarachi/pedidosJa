<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $orders = Order::orderBy('created_at', 'desc')->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No orders registered.',
            ], 200);
        }
        return OrderResource::collection($orders);
    }


    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new draft order",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "delivery_user_id", "order_date"},
     *             @OA\Property(property="reason", type="string", description="Description of the order"),
     *             @OA\Property(property="delivery_user_id", type="integer", description="ID of the delivery user"),
     *             @OA\Property(property="order_date", type="string", format="date", description="Date and time of the order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order_id", type="integer", description="ID of the created order")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input")
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
     *             @OA\Property(property="reason", type="string", example="Order description"),    
     *             @OA\Property(property="order_date", type="string", format="date", example="2024-11-07T12:00:00Z"),
     *             @OA\Property(property="state", type="string",  description="The order's current state"),  
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
     *                             @OA\Property(property="product_name", type="string", description="final name of the product"),
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
        if (!$order) {
            return response()->json([
                'message' => 'The requested order does not exist.',
            ], 404);
        }

        $order->load('deliveryUser', 'orderUsers.user', 'orderUsers.orderUserProducts');

        return new OrderResource($order);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Update an existing order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "delivery_user_id", "order_date"},
     *             @OA\Property(property="reason", type="string", description="Reason of the order"),
     *             @OA\Property(property="delivery_user_id", type="integer", description="ID of the delivery user"),
     *             @OA\Property(property="order_date", type="string", format="date", description="Date and time of the order"),
     *             @OA\Property(property="state", type="string",  enum={"draft", "in_process", "completed"}, description="The order's current state")         
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order_id", type="integer", description="ID of the updated order")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input or invalid state of the order"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function update(OrderRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        try {
            $order = $this->orderService->updateOrder($validated, $id);

            return response()->json(['order_id' => $order->id, 'state' => $order->state,], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Delete an Order",
     *     description="Deletes an Order by its ID.",
     *     operationId="deleteOrder",
     *      tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Order to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found - Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [Order].")
     *         )
     *     )
     * )
     */
    public function destroy(Order $order, OrderService $orderService)
    {
        try {
            $orderService->deleteOrder($order->id);

            return response()->json([
                'message' => 'Order deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
