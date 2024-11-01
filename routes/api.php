<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderUserController;
use App\Http\Controllers\Api\OrderUserProductController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-users', OrderUserController::class);
Route::apiResource('order-user-products', OrderUserProductController::class);
