<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost',
    ];

    /**
     * Obtiene la divisa base a la que pertenece este producto.
     * Un producto tiene una única divisa base.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Obtiene todos los precios asociados a este producto en diferentes divisas.
     * Un producto puede tener múltiples precios.
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
