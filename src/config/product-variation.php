<?php

return [
    "productVariationAdminRoutes" => true,
    "ordersSiteRoutes" => true,
    "orderStatesAdminRoutes" => true,

    // Facades.
    "variationFacade" => \PortedCheese\ProductVariation\Helpers\ProductVariationActionsManager::class,
    "orderFacade" => \PortedCheese\ProductVariation\Helpers\OrderActionsManager::class,

    // Order settings
    "orderNumberHasLetter" => true,
    "orderDigitsLength" => 8,
];