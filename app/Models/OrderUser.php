<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderUser extends Model
{


    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_user_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
