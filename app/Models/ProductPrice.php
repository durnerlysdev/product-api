<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'currency_id',
        'price',
    ];

    /**
     * Obtiene el producto al que pertenece este precio.
     * Un precio pertenece a un único producto.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtiene la divisa a la que pertenece este precio.
     * Un precio está asociado a una única divisa.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
