<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\ProductVariation\Admin",
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::get("/ajax/products/variations/specifications/{product}", "ProductVariationProductSpecificationController@available")
        ->name("products.variations.specifications.available");
});
