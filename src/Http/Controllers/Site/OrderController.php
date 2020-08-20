<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\ProductVariation;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function newSingle(Request $request, ProductVariation $variation)
    {
        return response()
            ->json([
                "success" => true,
                "message" => "Ваш заказ оформлен",
            ]);
    }
}
