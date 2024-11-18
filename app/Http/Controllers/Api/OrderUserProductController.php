<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUserProduct;
use App\Http\Requests\OrderUserProductRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderUser;
use App\Services\OrderUserProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class OrderUserProductController extends Controller
{
    private OrderUserProductService $orderUserProductService;

    public function __construct(OrderUserProductService $orderUserProductService)
    {
        $this->orderUserProductService = $orderUserProductService;
    }

    /**
     * @OA\Post(
     *     path="/api/order-user-products",
     *     tags={"Order User Products"},
     *     summary="Create a new order user product association",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"order_user_id", "product_id", "quantity", "amount_money"},
     *             @OA\Property(property="order_user_id", type="integer", description="ID of the order user"),
     *             @OA\Property(property="product_id", type="integer", description="ID of the product associated with the order user"),
     *             @OA\Property(property="quantity", type="integer", description="Quantity of the product"),
     *             @OA\Property(property="final_price", type="number", format="float", description="final price of the product"),
     *             @OA\Property(property="description", type="string", description="Description of the order"),
     *             @OA\Property(property="amount_money", type="number", format="float", description="Amount of money contributed by the user for the product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order user product created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Success message")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=404, description="Order user record not found")
     * )
     */
    public function store(OrderUserProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            // Usar el servicio para crear el registro
            $this->orderUserProductService->createOrderUserProduct($validated);

            return response()->json(['message' => 'Correctly associated products.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record in order_users does not exist.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
