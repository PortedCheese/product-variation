<?php

namespace PortedCheese\ProductVariation\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "user_data",
        "total",
    ];

    protected $casts = [
        "user_data" => "array",
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return "number";
    }

    /**
     * Статус заказа.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(\App\OrderState::class, "state_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
