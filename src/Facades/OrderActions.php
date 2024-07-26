<?php

namespace PortedCheese\ProductVariation\Facades;

use App\Order;
use App\OrderItem;
use App\OrderState;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;
use PortedCheese\ProductVariation\Helpers\OrderActionsManager;

/**
 * @method static recalculateOrderTotal(Order $order)
 * @method static addVariationsToOrder(Order $order, array $variationsInfo)
 * @method static addAddonVariationSetsToOrder(Order $order, $variation, $quantity = 1)
 * @method static makeOrderFromCart(Order $order, array $cartInfo)
 * @method static increaseOrderItemQuantity(OrderItem $orderItem, int $quantity)
 * @method static decreaseOrderItemQuantity(OrderItem $orderItem, int $quantity)
 * @method static bool|Model|OrderItem addItemToOrder(Order $order, $variation, $quantity = 1)
 * @method static OrderState getNewState()
 * @method static string generateUniqueNumber($letter = true, $length = 8)
 *
 * @see OrderActionsManager
 */
class OrderActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "order-actions";
    }
}