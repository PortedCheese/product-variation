<?php

namespace PortedCheese\ProductVariation\Facades;

use App\Order;
use App\OrderItem;
use App\OrderState;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;
use PortedCheese\ProductVariation\Helpers\OrderActionsManager;

/**
 * @method static addVariationsToOrder(Order $order, array $variationsInfo)
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