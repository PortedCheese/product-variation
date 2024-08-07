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
        "measurement",
        "specifications"
    ];


    public function getSpecificationsArrayAttribute()
    {
        return $this->specifications? json_decode($this->specifications) : [];
    }


    /**
     * Измерение
     * @return mixed|string
     */
    public function getShortMeasurementAttribute()
    {
        return ! empty($this->measurement) ? $this->measurement  : "шт";
    }

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
     * Вариация
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variation()
    {
        return $this->belongsTo(\App\ProductVariation::class);
    }

    /**
     * Сет этого дополнения
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderItemSet(){
        return $this->belongsTo(\App\OrderItemSet::class,"order_item_set_id");
    }

    /**
     * Сеты дополнений для позиции
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItemSets(){
        return $this->hasMany(\App\OrderItemSet::class,"order_item_id","id");
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
