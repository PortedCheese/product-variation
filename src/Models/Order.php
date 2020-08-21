<?php

namespace PortedCheese\ProductVariation\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use PortedCheese\ProductVariation\Notifications\NewOrderClient;

class Order extends Model
{
    use Notifiable;

    protected $fillable = [
        "user_data",
        "total",
    ];

    protected $casts = [
        "user_data" => "array",
    ];

    public static function getAttributesForRender()
    {
        return [
            "name" => "Имя",
            "email" => "E-mail",
            "phone" => "Телефон",
            "comment" => "Комментарий",
            "user_id" => "Идентификатор пользователя",
            "privacy_policy" => "Согласие с политикой конфиденциальности",
        ];
    }

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

    /**
     * Пользователь.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Позиции заказа.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(\App\OrderItem::class);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param $notification
     * @return \Illuminate\Config\Repository|mixed
     */
    public function routeNotificationForMail($notification)
    {
        return config("product-variation.clientNotifyEmail");
    }

    /**
     * Дата создания.
     *
     * @return \Carbon\Carbon|null
     */
    public function getCreatedHumanAttribute()
    {
        $created = $this->created_at;
        $changed = datehelper()->changeTz($created);
        return datehelper()->format($changed);
    }

    /**
     * Имя пользователя.
     *
     * @return string
     */
    public function getUserNameAttribute()
    {
        $data = $this->user_data;
        return ! empty($data["name"]) ? $data["name"] : "Не определено";
    }

    /**
     * E-mail пользователя.
     *
     * @return bool|string
     */
    public function getUserEmailAttribute()
    {
        $data = $this->user_data;
        return ! empty($data["email"]) ? $data["email"] : false;
    }

    /**
     * Телефон пользователя.
     *
     * @return bool|string
     */
    public function getUserPhoneAttribute()
    {
        $data = $this->user_data;
        return ! empty($data["phone"]) ? $data["phone"] : false;
    }
}
