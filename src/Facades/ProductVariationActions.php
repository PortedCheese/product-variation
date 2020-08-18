<?php

namespace PortedCheese\ProductVariation\Facades;

use Illuminate\Support\Facades\Facade;

class ProductVariationActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "product-variation-actions";
    }
}