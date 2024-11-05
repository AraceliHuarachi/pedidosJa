<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

/**
 * @OA\Tag(name="Products", description="API endpoints for managing products")
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get a list of products",
     *     @OA\Response(
     *         response=200,
     *         description="A list of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductResource")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request)
    {
        $products = Product::all();

        return ProductResource::collection($products);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(ProductRequest $request): Product
    {
        return Product::create($request->validated());
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get a specific product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to fetch",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show(Product $product): Product
    {
        return $product;
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $product->update($request->validated());

        return $product;
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Product deleted"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
