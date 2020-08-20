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

    public function updating(ProductVariation $variation)
    {
        $variation->fixSku(true);
    }
}
