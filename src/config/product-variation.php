<?php

return [
    "enableVariations" => true,
    "productVariationAdminRoutes" => true,
    "ordersSiteRoutes" => true,
    "orderStatesAdminRoutes" => true,
    "orderAdminRoutes" => true,

    // Facades.
    "variationFacade" => \PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager::class,
    "orderFacade" => \PortedCheese\ProductVariation\Helpers\OrderActionsManager::class,
    "variationFilterFacade" => \PortedCheese\ProductVariation\Helpers\ProductVariationFilterManager::class,

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
];