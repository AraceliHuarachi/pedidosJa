<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUser;
use App\Http\Requests\OrderUserRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderUserService;
use Illuminate\Http\JsonResponse;

class OrderUserController extends Controller
{
    private OrderUserService $orderUserService;

    public function __construct(OrderUserService $orderUserService)
    {
        $this->orderUserService = $orderUserService;
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
     * Update the specified resource in storage.
     */
    public function update(OrderUserRequest $request, OrderUser $orderUser): OrderUser
    {
        $orderUser->update($request->validated());

        return $orderUser;
    }

    public function destroy(OrderUser $orderUser): Response
    {
        $orderUser->delete();

        return response()->noContent();
    }
}
