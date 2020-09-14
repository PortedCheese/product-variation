<?php

namespace PortedCheese\ProductVariation\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        "sku",
        "price",
        "quantity",
        "total",
        "description",
        "title",
        "product_id",
        "variation_id",
    ];

    /**
     * Заказ.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(\App\Order::class);
    }

    /**
     * Товар.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }

    /**
     * Формат итого.
     *
     * @return string
     */
    public function getHumanPriceAttribute()
    {
        if ($this->price - intval($this->price) > 0) {
            return number_format($this->price, 2, ",", " ");
        }
        else {
            return number_format($this->price, 0, ",", " ");
        }
    }
}
