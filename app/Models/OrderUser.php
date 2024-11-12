<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderUser
 *
 * @property $id
 * @property $user_id
 * @property $order_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Order $order
 * @property User $user
 * @property OrderUserProduct[] $orderUserProducts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OrderUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'order_id', 'amount_money'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUserProducts()
    {
        return $this->hasMany(\App\Models\OrderUserProduct::class, 'order_user_id', 'id');
    }
}
