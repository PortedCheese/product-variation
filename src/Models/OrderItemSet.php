<?php

namespace PortedCheese\ProductVariation\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemSet extends Model
{
    protected $fillable = ["order_item_id","variation_id"];

    /**
     * Дополнительные позиции
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addons()
    {
        return $this->hasMany(\App\OrderItem::class,"order_item_set_id","id");
    }

    /**
     * Позиция
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderItem(){
        return $this->belongsTo(\App\OrderItem::class,"order_item_id")->whereNull("order_item_set_id");
    }


    /**
     * Вариация
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variation()
    {
        return $this->belongsTo(\App\ProductVariation::class, 'variation_id');
    }

}
