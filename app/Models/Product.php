<?php

namespace App\Models;

use App\Traits\TranslationsJsonField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Product
 *
 * @property $id
 * @property $name
 * @property $reference_price
 * @property $slug
 * @property $created_at
 * @property $updated_at
 *
 * @property OrderUserProduct[] $orderUserProducts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{
    use TranslationsJsonField;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'reference_price', 'slug', 'translations'];

    protected $casts = [
        'translations' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUserProducts()
    {
        return $this->hasMany(OrderUserProduct::class, 'product_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Generar el slug al crear o actualizar
        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }
}
