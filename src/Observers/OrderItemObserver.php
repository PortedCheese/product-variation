<?php

namespace PortedCheese\ProductVariation\Observers;

use App\OrderItem;

class OrderItemObserver
{
    public function creating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
        $product = $orderItem->product;
        $orderItem->title = $product->title;
    }

    public function updating(OrderItem $orderItem)
    {
        $orderItem->total = $orderItem->price * $orderItem->quantity;
    }
}
