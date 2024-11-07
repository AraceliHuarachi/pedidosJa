<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Pedidos API",
 *     version="1.0.0",
 *     description="API para manejar pedidos",
 * )
 *
 * @OA\Server(
 *     url="http://api-ari.local:8080/api",
 *     description="API server"
 * )
 *
 * @OA\Components(
 *     schemas={
 *         "OrderRequest"={
 *             type="object",
 *             required={"order"},
 *             properties={
 *                 "order"={
 *                     type="object",
 *                     properties={
 *                         "description"={type="string"},
 *                         "delivery_user_id"={type="integer"},
 *                         "order_date"={type="string", format="date"},
 *                         "order_users"={
 *                             type="array",
 *                             items={
 *                                 type="object",
 *                                 properties={
 *                                     "user_id"={type="integer"},
 *                                     "amount_money"={type="number", format="float"},
 *                                     "products"={
 *                                         type="array",
 *                                         items={
 *                                             type="object",
 *                                             properties={
 *                                                 "product_id"={type="integer"},
 *                                                 "quantity"={type="integer"},
 *                                                 "description"={type="string"},
 *                                                 "final_price"={type="number", format="float"}
 *                                             }
 *                                         }
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     }
 * )
 */
