<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @property $id
 * @property $reason 
 * @property $delicery_user_id
 * @property $d_user_name
 * @property $order_date
 * @property $state
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property OrderUser[] $orderUsers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Order extends Model
{ // Definir los estados como constantes
    const STATE_DRAFT = 'draft';
    const STATE_IN_PROCESS = 'in_process';
    const STATE_COMPLETED = 'completed';
    const STATE_CANCELED = 'canceled';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = ['reason', 'delivery_user_id', 'd_user_name', 'order_date', 'state'];

    /**
     * Establecer el estado a 'draft' por defecto cuando se crea la orden.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            if (is_null($order->state)) {
                $order->state = self::STATE_DRAFT;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function deliveryUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'delivery_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUsers()
    {
        return $this->hasMany(\App\Models\OrderUser::class, 'order_id');
    }
}
