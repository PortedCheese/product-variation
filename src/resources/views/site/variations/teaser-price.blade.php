<teaser-price :variations="{{ json_encode(product_variation()->getVariationsByProduct($product)) }}"
              product-url="{{ route("catalog.products.show", ["product" => $product]) }}">
</teaser-price>