<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'exchange_rate',
    ];

    /**
     * Obtiene los productos cuya divisa base es esta divisa.
     * Una divisa puede ser la base para múltiples productos.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'currency_id');
    }

    /**
     * Obtiene todos los registros de precios de productos que utilizan esta divisa.
     * Una divisa puede estar presente en los precios de múltiples productos.
     */
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
