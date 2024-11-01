<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderUser extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_user_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
