<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUser;
use App\Http\Requests\OrderUserRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderUserRequest $request)
    {
        // Acceder a los datos validados
        $validated = $request->validated();


        // Verificar que la orden esté en estado draft o in_process antes de permitir la creación
        $order = Order::findOrFail($validated['order_id']);
        if (!in_array($order->state, [Order::STATE_DRAFT, Order::STATE_IN_PROCESS])) {
            return response()->json(['error' => 'La orden no está en un estado válido para agregar usuarios'], 400);
        }

        // Crear el registro en order_users
        $orderUser = OrderUser::create([
            'order_id' => $validated['order_id'],
            'user_id' => $validated['user_id'],
            'amount_money' => null, // Se deja en null por ahora
        ]);

        // Retornar el ID del registro creado
        return response()->json(['order_user_id' => $orderUser->id], 201);
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
