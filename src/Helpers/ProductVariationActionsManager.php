<?php

namespace PortedCheese\ProductVariation\Helpers;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use PortedCheese\ProductVariation\Http\Resources\ProductVariation as VariationResource;

class ProductVariationActionsManager
{
    /**
     * @param Product $product
     * @param bool $getCollection
     * @return Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getVariationsByProduct(Product $product, $getCollection = false)
    {
        $collection = $product
            ->variations()
            ->orderBy("disabled_at")
            ->orderBy("price")
            ->get();
        if ($getCollection) {
            return $collection;
        }
        return VariationResource::collection($collection);
    }
}