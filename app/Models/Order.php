<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @property $id
 * @property $description
 * @property $user_id
 * @property $order_date
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

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = ['description', 'delivery_user_id', 'order_date', 'state'];

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
     * Cambiar el estado de la orden de manera controlada.
     *
     * @param string $newState
     * @return void
     */
    public function changeState(string $newState)
    {
        $validStates = [self::STATE_DRAFT, self::STATE_IN_PROCESS, self::STATE_COMPLETED];

        // Verificamos que el estado sea válido
        if (!in_array($newState, $validStates)) {
            throw new \InvalidArgumentException("Estado inválido");
        }

        // Asegurarnos de que la transición de estado sea válida
        if ($this->isTransitionValid($newState)) {
            $this->state = $newState;
            $this->save();
        } else {
            throw new \InvalidArgumentException("Transición de estado no permitida");
        }
    }

    /**
     * Verificar si la transición de estado es válida.
     *
     * @param string $newState
     * @return bool
     */
    protected function isTransitionValid(string $newState)
    {
        //Reglas de negocio para las transiciones
        switch ($this->state) {
            case self::STATE_DRAFT:
                return in_array($newState, [self::STATE_IN_PROCESS]); // De draft solo se puede ir a in_process
            case self::STATE_IN_PROCESS:
                return in_array($newState, [self::STATE_COMPLETED]); // De in_process solo se puede ir a completed
            case self::STATE_COMPLETED:
                return false; // Ya no se puede cambiar después de completado
            default:
                return false;
        }
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
