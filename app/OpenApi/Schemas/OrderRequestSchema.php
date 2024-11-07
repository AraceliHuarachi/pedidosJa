<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="OrderRequest",
 *     type="object",
 *     required={"order"},
 *     @OA\Property(
 *         property="order",
 *         type="object",
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="delivery_user_id", type="integer"),
 *         @OA\Property(property="order_date", type="string", format="date"),
 *         @OA\Property(
 *             property="order_users",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="amount_money", type="number", format="float"),
 *                 @OA\Property(
 *                     property="products",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="product_id", type="integer"),
 *                         @OA\Property(property="quantity", type="integer"),
 *                         @OA\Property(property="description", type="string"),
 *                         @OA\Property(property="final_price", type="number", format="float")
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class OrderRequestSchema {}
