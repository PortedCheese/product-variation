<?php

namespace PortedCheese\ProductVariation\Observers;

use App\Order;
use App\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PortedCheese\ProductVariation\Facades\OrderActions;

class OrderObserver
{
    /**
     * Перед сохранением.
     *
     * @param Order $order
     */
    public function creating(Order $order)
    {
        $order->number = OrderActions::generateUniqueNumber(
            config("product-variation.orderNumberHasLetter"),
            config("product-variation.orderDigitsLength")
        );

        $order->uuid = Str::uuid();

        if (Auth::check()) {
            $order->user_id = Auth::id();
        }

        $this->addState($order);
        $this->clearUserData($order);
    }

    /**
     * Статус заказа.
     *
     * @param Order $order
     */
    protected function addState(Order $order)
    {
        if (empty($order->state_id)) {
            $state = OrderActions::getNewState();
            $order->state_id = $state->id;
        }
    }

    /**
     * Очистить данные пользователя.
     *
     * @param Order $order
     */
    protected function clearUserData(Order $order)
    {
        $userData = $order->user_data;
        if (! empty($userData["_token"])) unset($userData["_token"]);
        if (! empty($userData["privacy_policy"])) unset($userData["privacy_policy"]);
        $order->user_data = $userData;
    }

    /**
     * После удаления.
     *
     * @param Order $order
     * @throws \Exception
     */
    public function deleted(Order $order)
    {
        foreach ($order->items as $item) {
            /**
             * @var OrderItem $item
             */
            $item->delete();
        }
    }
}
