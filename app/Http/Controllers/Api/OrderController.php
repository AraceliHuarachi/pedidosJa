<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\OrderUser;
use App\Models\OrderUserProduct;

class OrderController extends Controller
{
    /**
     * recibir datos enviados de .
     */
    public function storeFinalOrder(OrderRequest $request)
    {
        $data = $request->validated();

        // Utilizamos una transacción para asegurarnos de que todos los datos se guarden correctamente
        DB::beginTransaction();

        try {
            // Crear el pedido en la tabla `orders`
            $orderData = $data['order'];
            $order = Order::create([
                'description' => $orderData['description'],
                'delivery_user_id' => $orderData['delivery_user_id'],
                'order_date' => $orderData['order_date'],
            ]);

            // Iterar sobre cada usuario en `order_users` y guardar en la tabla `order_users`
            foreach ($orderData['order_users'] as $orderUserData) {
                $orderUser = OrderUser::create([
                    'user_id' => $orderUserData['user_id'],
                    'amount_money' => $orderUserData['amount_money'], // El dinero entregado en efectivo
                    'order_id' => $order->id,
                ]);

                // Guardar los productos para cada usuario en la tabla `order_user_products`
                foreach ($orderUserData['products'] as $productData) {
                    OrderUserProduct::create([
                        'order_users_id' => $orderUser->id,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                        'description' => $productData['description'] ?? null,
                        'final_price' => $productData['final_price'],
                    ]);
                }
            }

            // Confirmar la transacción si todo se guardó correctamente
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id,
            ], 201);
        } catch (\Exception $e) {
            // Si algo falla, revertimos la transacción
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): Order
    {
        return Order::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Order
    {
        return $order;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): Order
    {
        $order->update($request->validated());

        return $order;
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
