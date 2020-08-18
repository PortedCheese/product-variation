<admin-variation-list get-url="{{ route("admin.products.variations.index", ["product" => $product]) }}"
                      post-url="{{ route("admin.products.variations.store", ["product" => $product]) }}">
</admin-variation-list>