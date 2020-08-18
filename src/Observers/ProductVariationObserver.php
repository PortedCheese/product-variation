<?php

namespace PortedCheese\ProductVariation\Observers;

use App\ProductVariation;

class ProductVariationObserver
{
    /**
     * Перед сохранением.
     *
     * @param ProductVariation $variation
     */
    public function creating(ProductVariation $variation)
    {
        $variation->fixSku();
    }
}
