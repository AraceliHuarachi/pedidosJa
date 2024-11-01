<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUserProduct;
use Illuminate\Http\Request;
use App\Http\Requests\OrderUserProductRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderUserProductResource;

class OrderUserProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderUserProducts = OrderUserProduct::paginate();

        return OrderUserProductResource::collection($orderUserProducts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderUserProductRequest $request): OrderUserProduct
    {
        return OrderUserProduct::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderUserProduct $orderUserProduct): OrderUserProduct
    {
        return $orderUserProduct;
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
