<teaser-price :variations="{{ json_encode(product_variation()->getVariationsByProduct($product)) }}"
              product-url="{{ route("catalog.products.show", ["product" => $product]) }}"
              :specifications="{{ json_encode(product_variation()->getVariationsSpecificationsByProduct($product),JSON_FORCE_OBJECT) }} "
>
    @includeIf("variation-cart::site.variations.add-to-cart")
</teaser-price>