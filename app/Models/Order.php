<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a muchos con OrderUser
    public function orderUsers()
    {
        return $this->hasMany(OrderUser::class);
    }
}
