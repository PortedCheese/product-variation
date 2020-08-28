<?php

namespace PortedCheese\ProductVariation\Observers;

use App\ProductVariation;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

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

    /**
     * После создания.
     *
     * @param ProductVariation $variation
     */
    public function created(ProductVariation $variation)
    {
        $this->clearProductVariationsCache($variation);
    }

    /**
     * Перед обновлением.
     *
     * @param ProductVariation $variation
     */
    public function updating(ProductVariation $variation)
    {
        $variation->fixSku(true);
    }

    /**
     * После обновления.
     *
     * @param ProductVariation $variation
     */
    public function updated(ProductVariation $variation)
    {
        $this->clearProductVariationsCache($variation);
    }

    /**
     * После удаления.
     *
     * @param ProductVariation $variation
     */
    public function deleted(ProductVariation $variation)
    {
        $this->clearProductVariationsCache($variation);
    }

    /**
     * Очистить кэш.
     * @param ProductVariation $variation
     */
    protected function clearProductVariationsCache(ProductVariation $variation)
    {
        $product = $variation->product;
        ProductVariationActions::clearProductVariationsCache($product);
    }
}
