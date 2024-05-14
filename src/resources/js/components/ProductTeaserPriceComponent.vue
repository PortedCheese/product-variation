<template>
    <div class="variation-price mr-0">
        <product-variations :specifications="specifications" :variations="variations" v-model="chosenVariation" :show-choose="false"></product-variations>

        <div v-if="variationData && !specifications" class="variation-price__wrapper">
            <div class="variation-price__prices">
                <div class="rub-format variation-price__value">
                    <span class="rub-format__value">
                        <span v-if="variationData.measurement" class="rub-format__measurement">{{ variationData.short_measurement }}</span> {{ variationData.human_price }}
                    </span>
                    <svg class="rub-format__ico variation-price__rub">
                        <use xlink:href="#catalog-rub-bold"></use>
                    </svg>
                </div>
                <div class="rub-format variation-price__value variation-price__value_thin" v-if="variationData.sale">
                    <span class="rub-format__value">
                        {{ variationData.human_sale_price }}
                    </span>
                    <svg class="rub-format__ico variation-price__rub variation-price__rub_thin">
                        <use xlink:href="#catalog-rub"></use>
                    </svg>
                </div>
            </div>
            <div v-if="variationData.description" class="variation-price__description">
                {{ variationData.description }}
            </div>
        </div>

        <div class="variation-price__bottom">
            <slot v-if="variationData" :variation="variationData"></slot>

            <a :href="productUrl"
               v-if="variationData && variationsMoreCount >= 1"
               class="variation-price__more">
                <span v-if="! Object.keys(specifications).length">Еще {{ variationsMoreCount }} {{ variationMoreText }}</span>
                <span v-else>Еще варианты</span>
            </a>
            <span v-else class="variation-price__more variation-price__more_hidden">
                &nbsp;
            </span>
        </div>
    </div>
</template>

<script>
    import Variations from "./ChooseProductVariationComponent";

    export default {
        name: "ProductTeaserPriceComponent",

        components: {
            "product-variations": Variations
        },

        props: {
            variations: {
                type: Array,
                required: true
            },
            specifications:{
                type: Object,
                required: false
            },

            productUrl: {
                type: String,
                required: true
            }
        },

        data() {
            return {
                chosenVariation: "",
            }
        },

        computed: {
            variationData() {
                let variation = false;
                for (let item in this.variations) {
                    if (this.variations.hasOwnProperty(item)) {
                        if (this.variations[item].id === this.chosenVariation) {
                            variation = this.variations[item];
                        }
                    }
                }
                return variation;
            },

            variationsMoreCount() {
                return this.variations.length - 1;
            },

            variationMoreText() {
                let variables = ["вариант", "варианта", "вариантов"];
                let number = Math.abs(this.variationsMoreCount) % 100;
                let second = number % 10;
                if (number > 10 && number < 20) return variables[2];
                if (second > 1 && second < 5) return variables[1];
                if (second === 1) return variables[0];
                return variables[2];
            }
        }
    }
</script>

<style scoped>

</style>