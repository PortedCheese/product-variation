<?php

namespace PortedCheese\ProductVariation\Observers;

use App\Order;
use Illuminate\Support\Facades\Auth;
use PortedCheese\ProductVariation\Facades\OrderActions;

class OrderObserver
{
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
}
