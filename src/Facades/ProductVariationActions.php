<?php

namespace PortedCheese\ProductVariation\Facades;

use App\Category;
use App\Product;
use App\ProductVariation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager;

/**
 * @method static Collection|AnonymousResourceCollection getVariationsByProduct(Product $product, $getCollection = false)
 * @method static mixed getVariationsByAddon(Product $product, Product $parent)
 * @method static Mixed getVariationsSpecificationsByProduct(Product $product)
 * @method static Mixed getVariationsSpecificationsByAddon(Product $product, Product $parent)
 * @method static mixed getVariationSpecificationsArray(ProductVariation $variation)
 * @method static clearProductVariationsCache(Product $product)
 * @method static clearPricesCache(Category $category)
 * @method static object|bool getUserForVariation()
 * @method static array getPricesForCategory(Category $category, bool $includeSubs = false)
 * @method static array addPriceFilter(Category $category, array $specInfo, bool $includeSubs = false)
 * @method static getPriceQuery(array $range, bool $needBetween = true)
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