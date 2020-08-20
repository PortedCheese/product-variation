<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\ProductVariation\Site",
    "middleware" => ["web"],
    "as" => "catalog.",
    "prefix" => "orders",
], function () {
    Route::put("/new-single/{variation}", "OrderController@newSingle")
        ->name("orders.single");
});