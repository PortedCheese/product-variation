@php($policyRoute = \Illuminate\Support\Facades\Route::has("policy") ? "'" . route("policy") . "'" : false)
<div class="row">
    <div class="col-12">
        <order-single :variations="{{ json_encode(product_variation()->getVariationsByProduct($product)) }}"
                      :policy-url="{{ $policyRoute }}"
                      :user="{{ json_encode(product_variation()->getUserForVariation()) }}"
                      :specifications="{{ json_encode(product_variation()->getVariationsSpecificationsByProduct($product),JSON_FORCE_OBJECT) }}">
        </order-single>
    </div>
</div>