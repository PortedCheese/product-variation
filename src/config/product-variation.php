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

    // Order settings
    "orderNumberHasLetter" => true,
    "orderDigitsLength" => 8,

    // New order notify
    "clientNotifyEmail" => env("NEW_ORDER_NOTIFY_EMAIL", false),
    "enableClientNotification" => true,
    "enableUserNotification" => true,

    // Sort
    "enablePriceSort" => true,
];