<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function orderUsers()
    {
        return $this->belongsToMany(OrderUser::class, 'order_user_product')
            ->withPivot('quantity', 'price') // Acceso a las columnas de la tabla pivot
            ->withTimestamps(); // 
    }
}
