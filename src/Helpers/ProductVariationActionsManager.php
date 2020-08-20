<?php

namespace PortedCheese\ProductVariation\Helpers;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
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