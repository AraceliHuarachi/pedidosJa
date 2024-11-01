<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function users()
{
    return $this->belongsToMany(User::class, 'order_user');
}
}
