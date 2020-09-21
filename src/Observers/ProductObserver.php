<?php

namespace PortedCheese\ProductVariation\Observers;

use App\Product;
use App\ProductVariation;
use PortedCheese\BaseSettings\Exceptions\PreventDeleteException;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

class ProductObserver
{
    /**
     * @param Product $product
     * @throws PreventDeleteException
     */
    public function deleting(Product $product)
    {
        if ($count = $product->orderItems->count()) {
            throw new PreventDeleteException("Невозможно удалить товар, он находится в {$count} заказах");
        }
    }

    /**
     * @param Product $product
     * @throws \Exception
     */
    public function deleted(Product $product)
    {
        ProductVariationActions::clearProductVariationsCache($product);

        foreach ($product->variations as $variation) {
            /**
             * @var ProductVariation $variation
             */
            $variation->delete();
        }
    }
}
