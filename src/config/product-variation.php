<?php

return [
    "enableVariations" => true,
    "productVariationAdminRoutes" => true,
    "productVariationSpecificationAdminRoutes" => true,
    "ordersSiteRoutes" => true,
    "orderStatesAdminRoutes" => true,
    "orderAdminRoutes" => true,
    "measurementAdminRoutes" => true,

    // Facades.
    "variationFacade" => \PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager::class,
    "orderFacade" => \PortedCheese\ProductVariation\Helpers\OrderActionsManager::class,

    // Resources.
    "productVariationResource" => \PortedCheese\ProductVariation\Http\Resources\ProductVariation::class,

    // Order settings
    "orderNumberHasLetter" => true,
    "orderDigitsLength" => 8,

    // New order notify
    "clientNotifyEmail" => env("NEW_ORDER_NOTIFY_EMAIL", false),
    "enableClientNotification" => true,
    "enableUserNotification" => true,

    // Filter
    "enablePriceFilter" => true,
    "priceFilterKey" => "product_price",
    "enablePriceSort" => true,
    "priceSortReplaceNull" => 1000000000,

    // Addition
    "enableProductVariationsProductSpecifications" => true // true | false
];