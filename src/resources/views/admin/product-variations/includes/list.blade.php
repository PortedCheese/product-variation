@can("viewAny", \App\ProductVariation::class)
    <admin-variation-list get-url="{{ route("admin.products.variations.index", ["product" => $product]) }}"
                          :can-create="{{ \Illuminate\Support\Facades\Auth::user()->can("create", \App\ProductVariation::class) ? 1 : 0 }}"
                          post-url="{{ route("admin.products.variations.store", ["product" => $product]) }}">
    </admin-variation-list>
@endcan