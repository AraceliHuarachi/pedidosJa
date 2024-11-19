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
            $this->orderUserProductService->createOrderUserProduct($validated);

            return response()->json(['message' => 'Correctly associated products.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record in order_users does not exist.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/order-user-products/{id}",
     *     tags={"Order User Products"},
     *     summary="Update an existing product in an order-user association",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order-user-product record to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"product_id", "quantity", "final_price"},
     *             @OA\Property(property="product_id", type="integer", description="ID of the product"),
     *             @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
     *             @OA\Property(property="description", type="string", description="Additional details about the product", example="extra cheese"),
     *             @OA\Property(property="final_price", type="number", format="float", description="Final price of the product"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order-user-product record updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order_user_product_id", type="integer", description="ID of the updated order-user-product record")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input or invalid state of the order"),
     *     @OA\Response(response=404, description="Order-user-product record not found")
     * )
     */
    public function update(OrderUserProductRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        try {
            $orderUserProduct = $this->orderUserProductService->updateOrderUserProduct($validated, $id);

            return response()->json(['message' => 'Product updated successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record in order_user_product does not exist.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(OrderUserProduct $orderUserProduct): Response
    {
        $orderUserProduct->delete();

        return response()->noContent();
    }
}
