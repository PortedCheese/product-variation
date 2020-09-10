<template>
    <div class="form-group variation-price" v-if="showChoose">
        <div v-if="variationData" class="variation-price__wrapper">
            <div class="variation-price__prices">
                <div class="variation-price__value">
                    <span class="variation-price__value-text">
                        {{ variationData.human_price }}
                        <svg class="variation-price__rub">
                            <use xlink:href="#catalog-rub-bold"></use>
                        </svg>
                    </span>
                </div>
                <div class="variation-price__value variation-price__value_thin" v-if="variationData.sale">
                    <span class="variation-price__value-text">
                        {{ variationData.human_sale_price }}
                        <svg class="variation-price__rub  variation-price__rub_thin">
                            <use xlink:href="#catalog-rub"></use>
                        </svg>
                    </span>
                </div>
                <div v-if="variations.length === 1 && variationData.description">
                    {{ variationData.description }}
                </div>
            </div>
        </div>

        <div class="choose-variation" :class="variations.length <= 1 && variationData ? 'd-none' : ''">
            <div class="custom-control custom-radio choose-variation__item"
                 v-for="variation in variations">
                <input type="radio"
                       :id="'customRadio' + variation.id"
                       name="customRadio"
                       v-model="chosenVariation"
                       :value="variation.id"
                       :disabled="variation.disabled_at"
                       @change="$emit('change', chosenVariation)"
                       class="custom-control-input">
                <label class="custom-control-label choose-variation__label"
                       :for="'customRadio' + variation.id">
                    <span>
                        {{ variation.description }}
                    </span>
                    <span v-if="variation.disabled_at" class="choose-variation__prices">Нет в наличии</span>
                    <span v-else class="choose-variation__prices">
                        <span class="choose-variation__value">
                            {{ variation.human_price }}
                            <svg class="choose-variation__rub">
                                <use xlink:href="#catalog-rub-bold"></use>
                            </svg>
                        </span>

                        <span class="choose-variation__value choose-variation__value_thin" v-if="variation.sale">
                            {{ variation.human_sale_price }}
                            <svg class="choose-variation__rub choose-variation__rub_thin">
                                <use xlink:href="#catalog-rub"></use>
                            </svg>
                        </span>
                    </span>
                </label>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChooseProductVariationComponent",

        model: {
            prop: "chosen",
            event: "change"
        },

        props: {
            variations: {
                type: Array,
                required: true
            },

            chosen: {
                type: Number|String,
                required: true
            },

            showChoose: {
                type: Boolean,
                default: true
            }
        },

        data() {
            return {
                chosenVariation: "",
            }
        },

        created() {
            this.chosenVariation = this.chosen;
            if (! this.chosenVariation && this.variations.length) {
                for (let item in this.variations) {
                    if (this.variations.hasOwnProperty(item)) {
                        if (! this.variations[item].disabled_at) {
                            this.chosenVariation = this.variations[item].id;
                            this.$emit("change", this.chosenVariation);
                            break;
                        }
                    }
                }
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
            }
        }
    }
</script>

<style scoped>

</style>