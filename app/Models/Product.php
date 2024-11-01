<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property $id
 * @property $name
 * @property $reference_price
 * @property $created_at
 * @property $updated_at
 *
 * @property OrderUserProduct[] $orderUserProducts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'reference_price'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUserProducts()
    {
        return $this->hasMany(\App\Models\OrderUserProduct::class, 'id', 'product_id');
    }
}
