<?php

namespace PortedCheese\ProductVariation\Facades;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager;

/**
 * @method static Collection|AnonymousResourceCollection getVariationsByProduct(Product $product, $getCollection = false)
 * @method static object|bool getUserForVariation()
 *
 * @see ProductVariationActionsManager
 */
class ProductVariationActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-variation-actions";
    }
}