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
     * Увеличить количество.
     *
     * @param $quantity
     * @return bool
     */
    public function increaseQuantity($quantity)
    {
        $this->quantity += $quantity;
        $this->save();
        return true;
    }

    /**
     * Уменьшить количество.
     *
     * @param $quantity
     * @return bool
     */
    public function decreaseQuantity($quantity)
    {
        if ($this->quantity > $quantity) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }
}
