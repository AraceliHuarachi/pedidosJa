<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderUser;
use Illuminate\Http\Request;
use App\Http\Requests\OrderUserRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderUserResource;

class OrderUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderUsers = OrderUser::paginate();

        return OrderUserResource::collection($orderUsers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderUserRequest $request): OrderUser
    {
        return OrderUser::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderUser $orderUser): OrderUser
    {
        return $orderUser;
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
