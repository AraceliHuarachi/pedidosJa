<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderUserProduct
 *
 * @property $id
 * @property $order_users_id
 * @property $product_id
 * @property $quantity
 * @property $description
 * @property $final_price
 * @property $created_at
 * @property $updated_at
 *
 * @property OrderUser $orderUser
 * @property Product $product
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OrderUserProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['order_user_id', 'product_id', 'quantity', 'description', 'final_price'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderUser()
    {
        return $this->belongsTo(\App\Models\OrderUser::class, 'order_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }
}
