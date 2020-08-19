<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PortedCheese\ProductVariation\Http\Resources\ProductVariation as VariationResource;

class ProductVariationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(ProductVariation::class, "variation");
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Product $product)
    {
        $collection = $product
            ->variations()
            ->orderBy("disabled_at")
            ->orderBy("price")
            ->get();
        return response()
            ->json([
                "items" => VariationResource::collection($collection)
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Product $product)
    {
        $this->storeValidator($request->all(), $product);
        $product->variations()->create($request->all());
        return response()
            ->json([
                "success" => true,
                "message" => "Вариация успешно добавлена",
            ]);
    }

    /**
     * @param $data
     * @param Product $product
     */
    protected function storeValidator($data, Product $product)
    {
        Validator::make($data, [
            "sku" => ["nullable", "max:150", "unique:product_variations,sku"],
            "price" => ["required", "numeric", "min:0"],
            "sale_price" => ["nullable", "numeric", "min:0"],
            "description" => ["required", "max:100"],
        ], [], [
            "sku" => "Артикул",
            "price" => "Цена",
            "sale_price" => "Старая цена",
            "description" => "Описание",
        ])->validate();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductVariation  $variation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVariation $variation)
    {
        $this->updateValidator($request->all(), $variation);
        $variation->update($request->all());
        return response()
            ->json([
                "success" => true,
                "message" => "Вариация успешно обновлена",
            ]);
    }

    /**
     * @param $data
     * @param ProductVariation $variation
     */
    protected function updateValidator($data, ProductVariation $variation)
    {
        $id = $variation->id;
        Validator::make($data, [
            "sku" => ["nullable", "max:150", "unique:product_variations,sku,{$id}"],
            "price" => ["required", "numeric", "min:0"],
            "sale_price" => ["nullable", "numeric", "min:0"],
            "description" => ["required", "max:100"],
        ], [], [
            "sku" => "Артикул",
            "price" => "Цена",
            "sale_price" => "Старая цена",
            "description" => "Описание",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductVariation $variation
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(ProductVariation $variation)
    {
        $variation->delete();
        return response()
            ->json([
                "success" => true,
                "message" => "Вариация успешно удалена",
            ]);
    }

    /**
     * Отключение/включение вариации.
     *
     * @param ProductVariation $variation
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disable(ProductVariation $variation)
    {
        $this->authorize("disable", $variation);
        $variation->disabled_at = $variation->disabled_at ? null : now();
        $variation->save();
        return response()
            ->json([
                "success" => true,
                "message" => "Статус вариации изменен",
            ]);
    }
}
