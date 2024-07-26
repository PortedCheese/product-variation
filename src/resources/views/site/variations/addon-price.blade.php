<order-addon :variations="{{ json_encode(product_variation()->getVariationsByAddon($product, $parent)) }}"
              :specifications="{{ json_encode(product_variation()->getVariationsSpecificationsByAddon($product, $parent),JSON_FORCE_OBJECT) }} "
>
    @includeIf("variation-cart::site.variations.addon-to-order-items")
</order-addon>