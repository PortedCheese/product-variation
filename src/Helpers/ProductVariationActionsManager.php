<?php

namespace PortedCheese\ProductVariation\Helpers;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $key = "product-variation-actions-getVariationsByProduct:{$product->id}";
        $collection = Cache::rememberForever($key, function() use ($product) {
            return $product->variations()
                ->orderBy("disabled_at")
                ->orderBy("price")
                ->get();
        });
        if ($getCollection) {
            return $collection;
        }
        return VariationResource::collection($collection);
    }

    /**
     * Очистить кэш.
     *
     * @param Product $product
     */
    public function clearProductVariationsCache(Product $product)
    {
        Cache::forget("product-variation-actions-getVariationsByProduct:{$product->id}");
    }

    /**
     * Получить пользователя.
     *
     * @return bool|object
     */
    public function getUserForVariation()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return (object) [
                "id" => $user->id,
                "email" => $user->email,
                "name" => $user->full_name,
            ];
        }
        else {
            return false;
        }
    }
}