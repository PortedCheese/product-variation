<?php

namespace PortedCheese\ProductVariation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        $array = parent::toArray($request);
        $array['human_price'] = $this->human_price;
        $array['human_sale_price'] = $this->human_sale_price;
        if (strstr($request->route()->getName(), "admin") !== false) {
            $array["deleteUrl"] = route("admin.variations.destroy", ["variation" => $this]);
            $array["updateUrl"] = route("admin.variations.update", ["variation" => $this]);
        }
        return $array;
    }
}
