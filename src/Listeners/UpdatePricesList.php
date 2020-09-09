<?php

namespace PortedCheese\ProductVariation\Listeners;

use PortedCheese\CategoryProduct\Events\ProductListChange;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

class UpdatePricesList
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductListChange  $event
     * @return void
     */
    public function handle(ProductListChange $event)
    {
        $category = $event->category;
        ProductVariationActions::clearPricesCache($category);
    }
}
