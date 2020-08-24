<?php

namespace PortedCheese\ProductVariation\Observers;

use App\OrderItem;

class OrderItemObserver
{
    /**
     * Перед сохранением.
     *
     * @param OrderItem $orderItem
     */
    public function creating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
        $product = $orderItem->product;
        $orderItem->title = $product->title;
    }

    /**
     * Перед обновлением.
     *
     * @param OrderItem $orderItem
     */
    public function updating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
    }
}
