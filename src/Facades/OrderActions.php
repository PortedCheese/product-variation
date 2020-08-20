<?php

namespace PortedCheese\ProductVariation\Facades;

use App\OrderState;
use Illuminate\Support\Facades\Facade;
use PortedCheese\ProductVariation\Helpers\OrderActionsManager;

/**
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