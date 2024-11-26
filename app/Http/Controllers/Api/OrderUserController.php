<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUser;
use App\Http\Requests\OrderUserRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderUserResource;
use App\Models\Order;
use App\Services\OrderUserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class OrderUserController extends Controller
{
    private OrderUserService $orderUserService;

    public function __construct(OrderUserService $orderUserService)
    {
        $this->orderUserService = $orderUserService;
    }
    /**
     * @OA\Get(
     *     path="/api/order-users/{id}",
     *     summary="Get details of a specific OrderUser",
     *     description="Retrieve the details of a specific OrderUser by its ID, including related products.",
     *     operationId="showOrderUser",
     *     tags={"Order Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the OrderUser",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OrderUser details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="order_id", type="integer", example=5),
     *             @OA\Property(property="user_id", type="integer", example=4),
     *             @OA\Property(property="user_name", type="string", description="final name of the user"),
     *             @OA\Property(property="amount_money", type="number", format="float", description="Amount of money given by the user"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-11T12:32:54Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-11T12:32:54Z"),
     *             @OA\Property(property="products", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="product_name", type="string", description="Name of the product"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="description", type="string", example="Product description"),
     *                     @OA\Property(property="final_price", type="string", example="20.00")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="OrderUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OrderUser not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve OrderUser details"),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function show(OrderUser $orderUser): OrderUserResource
    {
        $orderUser->load('user', 'orderUserProducts');

        return new OrderUserResource($orderUser);
    }

    /**
     * @OA\Post(
     *     path="/api/order-users",
     *     tags={"Order Users"},
     *     summary="Create a new order user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"order_id", "user_id"},
     *             @OA\Property(property="order_id", type="integer", description="ID of the order"),
     *             @OA\Property(property="user_id", type="integer", description="ID of the user associated with the order"),
     *             @OA\Property(property="user_name", type="string", description="final name of the user", example="Jhon"),
     *             @OA\Property(property="amount_money", type="number", format="float", description="Amount of money given by the user (optional, can be null)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order user created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order_user_id", type="integer", description="ID of the created order user")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(OrderUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $orderUser = $this->orderUserService->createOrderUser($validated);

            return response()->json(['order_user_id' => $orderUser->id], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/order-users/{id}",
     *     tags={"Order Users"},
     *     summary="Update an existing order-user association",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order-user record to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", description="ID of the user associated with the order"),
     *             @OA\Property(property="user_name", type="string", description="final name of the user", example=Jhon),
     *             @OA\Property(property="amount_money", type="number", format="float", description="Amount of money contributed by the user"),
     *             )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order-user association updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order_user_id", type="integer", description="ID of the updated order-user record")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input or invalid state of the order"),
     *     @OA\Response(response=404, description="Order-user record not found")
     * )
     */
    public function update(OrderUserRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        try {
            $orderUser = $this->orderUserService->updateOrderUser($validated, $id);

            return response()->json(['order_user_id' => $orderUser->id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/order-users/{id}",
     *     summary="Delete an OrderUser",
     *     description="Deletes an OrderUser by its ID.",
     *     operationId="deleteOrderUser",
     *      tags={"Order Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the OrderUser to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No Content - OrderUser successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found - OrderUser not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [OrderUser].")
     *         )
     *     )
     * )
     */

    public function destroy(OrderUser $orderUser, OrderUserService $orderUserService)
    {
        try {
            $orderUserService->deleteOrderUser($orderUser->id);

            return response()->json([
                'message' => 'Order_User deleted succesfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order_User not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
