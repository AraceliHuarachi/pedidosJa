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
     * Crear un nuevo registro en order_user_products.
     *
     * @param OrderUserProductRequest $request
     * @return JsonResponse
     */
    public function store(OrderUserProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            // Usar el servicio para crear el registro
            $this->orderUserProductService->createOrderUserProduct($validated);

            return response()->json(['message' => 'Productos asociados correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El registro en order_users no existe.'], 404);
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
