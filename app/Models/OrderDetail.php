<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_user', 'order_id', 'id');
    }
    public function user()
    {
        return $this->belongsToMany(User::class, 'order_user', 'user_id', 'id');
    }
}
