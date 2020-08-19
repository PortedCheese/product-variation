<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\ProductVariation\Admin",
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::resource("products.variations", "ProductVariationController")
        ->except("show", "edit", "create")
        ->shallow();
    Route::put("/variations/{variation}/disable", "ProductVariationController@disable")
        ->name("variations.disable");
});