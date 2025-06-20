<?php

namespace PortedCheese\ProductVariation\Listeners;

use PortedCheese\CategoryProduct\Events\CategorySpecificationUpdate;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

class ProductVariationSpecificationUpdate
{
    public function handle(CategorySpecificationUpdate $event)
    {
        $category = $event->category;
        foreach ($category->products()->get() as $product){
            ProductVariationActions::clearProductVariationsCache($product);
        }
        if (config("category-product.useAddons")) {
            foreach ($category->addons()->get() as $product){
                ProductVariationActions::clearProductVariationsCache($product);
            }
        }

    }
}