<?php

namespace PortedCheese\ProductVariation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductVariation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model = $this->resource;
        $array = parent::toArray($request);
        $array['human_price'] = $model->human_price;
        $array['human_sale_price'] = $model->human_sale_price;
        if (strstr($request->route()->getName(), "admin") !== false) {
            $user = Auth::user();
            $array["deleteUrl"] = $user->can("delete", $model) ? route("admin.variations.destroy", ["variation" => $model]) : false;
            $array["updateUrl"] = $user->can("update", $model) ? route("admin.variations.update", ["variation" => $model]) : false;
            $array["disableUrl"] = $user->can("disable", $model) ? route("admin.variations.disable", ["variation" => $model]) : false;
        }
        else {
            $array["orderSingleUrl"] = route("catalog.orders.single", ["variation" => $model]);
            if (config("variation-cart")) {
                $array["addToCartUrl"] = route("catalog.cart.add", ["variation" => $model]);
            }
        }
        return $array;
    }
}
