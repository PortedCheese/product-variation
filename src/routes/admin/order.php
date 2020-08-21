<?php

use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "App\Http\Controllers\Vendor\ProductVariation\Admin",
    "middleware" => ["web", "management"],
    "as" => "admin.",
    "prefix" => "admin",
], function () {
    Route::resource("orders", "OrderController")
        ->except("create", "store", "edit");
});