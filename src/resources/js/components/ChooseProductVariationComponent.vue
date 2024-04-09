<template>
    <div class="form-group variation-price" v-if="showChoose">
        <div v-if="variationData" class="variation-price__wrapper">
            <div class="variation-price__prices">
                <div class="rub-format variation-price__value">
                    <span class="rub-format__value">
                        <span class="rub-format__measurement" v-if="variationData.measurement">{{ variationData.short_measurement }}</span>
                      {{ variationData.human_price }}
                    </span>
                    <svg class="rub-format__ico">
                        <use xlink:href="#catalog-rub-bold"></use>
                    </svg>
                </div>
                <div class="rub-format variation-price__value variation-price__value_thin" v-if="variationData.sale">
                    <span class="rub-format__value">
                        {{ variationData.human_sale_price }}
                    </span>
                    <svg class="rub-format__ico">
                        <use xlink:href="#catalog-rub"></use>
                    </svg>
                </div>
                <div v-if="variations.length === 1 && variationData.description">
                    {{ variationData.description }}
                </div>
            </div>
        </div>

        <div class="choose-variation" v-if="!variationData">
            <span class="choose-variation__unavailabe">Нет в наличии</span>
        </div>
        <div class="choose-variation" v-if="variations.length > 1 && variationData">
            <div class="сustom-control custom-radio choose-variation__item"
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
                      v-if="variation.disabled_at"
                      :for="'customRadio' + variation.id">
                   <span>
                       {{ variation.description }}
                   </span>
                  <span class="choose-variation__prices">Нет в наличии</span>
                </label>
                <label class="custom-control-label choose-variation__label"
                       v-else
                       :for="'customRadio' + variation.id">
                    <span>
                        {{ variation.description }}
                    </span>
                    <span class="rub-format choose-variation__value">
                        <span class="rub-format__value">
                            <span v-if="variation.measurement" class="rub-format__measurement">{{ variation.short_measurement }}</span> {{ variation.human_price }}
                        </span>
                        <svg class="rub-format__ico">
                            <use xlink:href="#catalog-rub"></use>
                        </svg>
                    </span>
                    <span class="rub-format choose-variation__value choose-variation__value_thin" v-if="variation.sale">
                        <span class="rub-format__value">
                            {{ variation.human_sale_price }}
                        </span>
                        <svg class="rub-format__ico">
                            <use xlink:href="#catalog-rub"></use>
                        </svg>
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