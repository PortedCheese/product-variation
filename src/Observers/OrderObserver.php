<?php

namespace PortedCheese\ProductVariation\Observers;

use App\Order;
use App\OrderItem;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::check()) {
            $order->user_id = Auth::id();
        }

        if (empty($order->state_id)) {
            $state = OrderActions::getNewState();
            $order->state_id = $state->id;
        }
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
