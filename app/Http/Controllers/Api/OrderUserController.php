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
