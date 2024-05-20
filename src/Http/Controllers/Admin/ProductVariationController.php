<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Image;
use App\Measurement;
use App\Product;
use App\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PortedCheese\CategoryProduct\Facades\ProductActions;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

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
        return response()
            ->json([
                "items" => ProductVariationActions::getVariationsByProduct($product)
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

        $variation = $product->variations()->create($request->all());
        try
        {
            $measure = Measurement::find($request->get("measurement"));
            if (isset($variation->measurement_id)){
                $variation->measurement_id = $measure->id;
                $variation->save();
            }
        }
        catch (\Exception $e){
            Log::error($e);
        }
        if (! $request->get("image_id"))
            $variation->clearImage();
        else{
            try
            {
                $image = Image::find($request->get("image_id"));
                if (isset($image)){
                    $variation->product_image_id = $image->id;
                    $variation->save();
                }
            }
            catch (\Exception $e){
                Log::error($e);
            }
        }

        try
        {
            $specifications = $request->get("specificationIds");
            $variation->specifications()->sync($specifications);
        }
        catch (\Exception $e){
            Log::error($e);
        }
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
            "measurement" => ["nullable", "max:150", "exists:measurements,id"],
            "price" => ["required", "numeric", "min:0"],
            "sale_price" => ["nullable", "numeric", "min:0"],
            "description" => ["required", "max:100"],
            "specificationIds" => ["nullable", "array"],
            "imageId" => ["nullable", "numeric"],
        ], [], [
            "sku" => "Артикул",
            "measurements" => "Измерение",
            "price" => "Цена",
            "sale_price" => "Старая цена",
            "description" => "Описание",
            "specificationIds" => "Характеристики",
            "imageId" => "Изображение",
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
        try
        {
            $measure = Measurement::find($request->get("measurement"));
            $variation->measurement_id = $measure->id;
        }
        catch (\Exception $e){
            $variation->measurement_id = null;
        }
        try
        {
            $specifications = $request->get("specificationIds");
            $variation->specifications()->sync($specifications);
            ProductVariationActions::clearProductVariationsCache($variation->product);
        }
        catch (\Exception $e){
            Log::error($e);
        }

        if (! $request->get("image_id"))
            $variation->clearImage();
        else{
            try
            {
                $image = Image::find($request->get("image_id"));
                if (isset($image)){
                    $variation->product_image_id = $image->id;
                }
            }
            catch (\Exception $e){
                Log::error($e);
            }
        }

        $variation->save();

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
            "measurement" => ["nullable", "max:150", "exists:measurements,id"],
            "price" => ["required", "numeric", "min:0"],
            "sale_price" => ["nullable", "numeric", "min:0"],
            "description" => ["required", "max:100"],
            "specificationIds" => ["nullable", "array"],
            "imageId" => ["nullable", "numeric"],
        ], [], [
            "sku" => "Артикул",
            "measurement" => "Измерение",
            "price" => "Цена",
            "sale_price" => "Старая цена",
            "description" => "Описание",
            "specificationIds" => "Характеристики",
            "imageId" => "Изображение",
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
