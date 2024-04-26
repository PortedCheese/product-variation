@can("viewAny", \App\ProductVariation::class)
    <admin-variation-list get-url="{{ route("admin.products.variations.index", ["product" => $product]) }}"
                          :measurements="{{ \App\Measurement::all()}}"
                          :can-create="{{ \Illuminate\Support\Facades\Auth::user()->can("create", \App\ProductVariation::class) ? 1 : 0 }}"
                          :can-add-specifications="{{ config("product-variation.enableProductVariationsProductSpecifications")? 1 : 0  }}"
                          post-url="{{ route("admin.products.variations.store", ["product" => $product]) }}"
                          spec-url="{{ route("admin.products.variations.specifications.available", ["product" => $product]) }}"
    >
    </admin-variation-list>
@endcan