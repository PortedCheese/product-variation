<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\ProductVariation\Admin",
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::resource("order-states", "OrderStateController")->parameters([
        "order-states" => "state"
    ]);
});