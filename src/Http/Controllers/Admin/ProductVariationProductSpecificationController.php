<?php

namespace PortedCheese\ProductVariation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Measurement;
use App\Product;
use App\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PortedCheese\CategoryProduct\Facades\ProductActions;
use PortedCheese\ProductVariation\Facades\ProductVariationActions;

class ProductVariationProductSpecificationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(ProductVariation::class, "variation");
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function available(Product $product)
    {
        $available = ProductActions::getAvailableSpecifications($product);
        $items =  ProductActions::getProductSpecifications($product, true);
        $values = [];
        foreach ($items as $item){
            $values[$item->specification_id][] = [
                "id" => $item->id,
                "specification_id " => $item->specification_id,
                "title" => $item->title,
                "value" => $item->value,
                "code" => $item->code];

        }
        $specifications = [];
        foreach ($available as $spec){
            if (isset($values[$spec["id"]])) {
                $specifications[] = $spec;
            }
        }

        return response()
            ->json([
                "items" => $values,
                "available" => $specifications,
            ]);
    }

}
