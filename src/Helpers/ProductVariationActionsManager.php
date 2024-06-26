<?php

namespace PortedCheese\ProductVariation\Helpers;

use App\Category;
use App\Product;
use App\ProductVariation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PortedCheese\CategoryProduct\Facades\ProductActions;

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
        $class = config("product-variation.productVariationResource");
        return $class::collection($collection);
    }

    /**
     * @param Product $product
     * @return mixed
     *
     */
    public function getVariationsSpecificationsByProduct(Product $product)
    {
        $key = "product-variation-actions-getVariationsSpecificationsByProduct:{$product->id}";
        $collection = Cache::rememberForever($key, function() use ($product) {
            $variations = $this->getVariationsByProduct($product);
            $result = [];
            $merge = [];
            $specifications = [];
            foreach ($variations as $variation){
                if (! $variation->disabled_at)
                    $specifications[] = $variation->specificationsArray;
            }

            foreach ($specifications as $key => $item){
                $merge = array_merge($merge, $item);
            }
            $merge = array_unique($merge, SORT_REGULAR);
            foreach ($merge as $item) {
                $result[$item->title][] = [
                    "id"=> $item->id,
                    "specification_id"=> $item->specification_id,
                    "value" => $item->value,
                    "code" => $item->code
                ];
            }
            return $result ;
        });

        return $collection;
    }

    /**
     * Очистить кэш.
     *
     * @param Product $product
     */
    public function clearProductVariationsCache(Product $product)
    {
        Cache::forget("product-variation-actions-getVariationsByProduct:{$product->id}");
        Cache::forget("product-variation-actions-getVariationsSpecificationsByProduct:{$product->id}");
        $category = $product->category;
        $this->clearPricesCache($category);
    }

    /**
     * Очистить кэш цен.
     *
     * @param Category $category
     */
    public function clearPricesCache(Category $category)
    {
        $key = "product-variation-actions-getPricesForCategory:{$category->id}";
        Cache::forget("$key-true");
        Cache::forget("$key-false");
        if ($category->parent_id) {
            $this->clearPricesCache($category->parent);
        }
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
                "phone" => $user->phone_number,
                "model" => $user,
            ];
        }
        else {
            return false;
        }
    }

    /**
     * Цены для категории.
     *
     * @param Category $category
     * @param bool $includeSubs
     * @return mixed
     */
    public function getPricesForCategory(Category $category, bool $includeSubs = false)
    {
        $key = "product-variation-actions-getPricesForCategory:{$category->id}";
        $key .= $includeSubs ? "-true" : "-false";
        $pIds = ProductActions::getCategoryProductIds($category, $includeSubs);
        return Cache::rememberForever($key, function() use ($pIds) {
            $variations = ProductVariation::query()
                ->select("id", "price")
                ->whereIn("product_id", $pIds)
                ->whereNull("disabled_at")
                ->get();
            $prices = [];
            foreach ($variations as $variation) {
                $price = $variation->price;
                if ($price >= 0 && ! in_array($price, $prices)) {
                    $prices[] = $price;
                }
            }
            return $prices;
        });
    }

    /**
     * Добавить фильтр по цене.
     *
     * @param Category $category
     * @param array $specInfo
     * @param bool $includeSubs
     *
     * @return array
     */
    public function addPriceFilter(Category $category, array $specInfo, bool $includeSubs = false)
    {
        $prices = $this->getPricesForCategory($category, $includeSubs);
        if (! empty($prices)) {
            array_unshift($specInfo, (object) [
                "id" => 0,
                "title" => "Цена",
                "filter" => 1,
                "type" => "range",
                "slug" => config("product-variation.priceFilterKey"),
                "group_id" => 0,
                "priority" => 0,
                "values" => $prices,
            ]);
        }
        return $specInfo;
    }

    /**
     * Запрос цен.
     *
     * @param array $range
     * @param bool $needBetween
     * @return \Illuminate\Database\Query\Builder
     */
    public function getPriceQuery(array $range, bool $needBetween = true)
    {
        $query = DB::table("product_variations")
            ->select("price", "product_id", DB::raw("count(product_id) as count"), DB::raw("min(price) as minimal"))
            ->whereNull("disabled_at");
        if ($needBetween && ! empty($range["from"]) && ! empty($range["to"])) {
            $from = $range["from"];
            $to = $range["to"];
            $query->whereBetween("price", [$from, $to + 1]);
        }
        return $query->orderBy("price")
            ->groupBy("product_id");
    }
}