<?php

if (! function_exists("product_variations")) {
    /**
     * @return \PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager
     */
    function product_variation() {
        return app("product-variation-actions");
    }
}