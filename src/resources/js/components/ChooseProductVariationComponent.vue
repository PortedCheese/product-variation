<template>
    <div :class="showChoose? 'form-group variation-price':''" v-if="showChoose || specifications">
        <div class="variation-price__specifications" v-if="Object.keys(specifications).length">
            <choose-specification-component :available="specifications"
                                            :chose="chose" @changeChoosing="changeChose"
                                            :variations="variations"
                                            :current="this.variationData.specifications"
                                            :full-mode="showChoose"
            ></choose-specification-component>
        </div>
        <div v-if="variationData" class="variation-price__wrapper">
            <div class="product-gallery-btns float-right">
                <div v-if="variationData.product_image_id && showChoose" :data-selector="'.cell'+variationData.product_image_id" class="btn">
                    <img :src="variationData.product_image_url"
                         class="img-thumbnail"
                         :alt="variationData.description">
                </div>
            </div>
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
            <div v-if="variationData.specifications" class="variation-price__about">
                <span class="text-muted me-2" v-for="spec in variationData.specifications">{{ spec.value }}</span>
            </div>
        </div>

        <div class="choose-variation" v-if="!variationData">
            <span class="choose-variation__unavailabe">Нет в наличии</span>
        </div>

        <div class="choose-variation" v-if="showChoose && specVariations.length > 1 && variationData">
            <div class="form-check choose-variation__item"
                 v-for="variation in specVariations">
                <input type="radio"
                       :id="'customRadio' + variation.id"
                       name="customRadio"
                       v-model="chosenVariation"
                       :value="variation.id"
                       :disabled="variation.disabled_at"
                       @change="setChosenVariation()"
                       class="form-check-input">
                <label class="form-check-label choose-variation__label"
                      v-if="variation.disabled_at"
                      :for="'customRadio' + variation.id">
                    <p class="mb-0">
                        {{ variation.description }}<br>
                        <span class="text-muted me-2" v-for="spec in variation.specifications">{{ spec.value }}</span>
                    </p>
                  <span class="choose-variation__prices">Нет в наличии</span>
                </label>
                <label class="form-check-label choose-variation__label"
                       v-else
                       :for="'customRadio' + variation.id">
                    <p class="mb-0">
                        {{ variation.description }}<br>
                        <span class="text-muted me-2" v-for="spec in variation.specifications">{{ spec.value }}</span>
                    </p>
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
    import productVariationEventBus from '../category-product/categoryProductEventBus';
    import ChooseSpecificationComponent from "./ChooseSpecificationComponent";
    export default {
        name: "ChooseProductVariationComponent",
        components: {ChooseSpecificationComponent},
        model: {
            prop: "chosen",
            event: "change"
        },

        props: {
            variations: {
                type: Array,
                required: true
            },

            specifications: {
                type: Object,
                required: false
            },

            chosen: {
                type: Number|String,
                required: true
            },

            showChoose: {
                type: Boolean,
                default: true
            },

            pageMode: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                chosenVariation: "",
                chose: [],
            }
        },

        created() {
            this.chosenVariation = this.chosen;
            this.setChosenVariation();
        },

        mounted() {
            // first load: send chosen product specs to addon's ChooseSpecification
            if (this.pageMode){
                productVariationEventBus.$on("get-choosing-product-spec",this.sendProductSpecs());
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
            specVariations() {
                let specVariations = [];
                if (this.specifications && this.chose.length) {
                    for (let item of this.variations) {
                        let push = 0;
                        if (item.specifications){
                            for (let spec of item.specifications) {
                                for (let ch of this.chose) {
                                    if (spec.id === ch.id) {
                                        push++;
                                        break;
                                    }
                                }
                            }

                        }
                        if (push == this.chose.length)
                            specVariations.push(item)
                    }
                }
                else
                    specVariations =  this.variations;
                return specVariations;
            }
        },

        methods: {
            changeChose(data) {
                this.chose = data["spec"];
                this.setChosenVariation(true);
            },
            sendProductSpecs(){
                // send chosen product spec to addon's ChooseSpecification
                if (this.pageMode && this.variationData) {
                    productVariationEventBus.$emit("change-choosing-product-spec", this.variationData.specifications ? this.variationData.specifications: []);
                }
            },
            setChosenVariation(spec = false){
                // chosen by input option
                if (!spec && this.chosenVariation){
                    this.$emit("change", this.chosenVariation);
                    this.sendProductSpecs();
                }
                // chosen by btns
                if (spec && ! this.specVariations.length)
                {
                    this.chosenVariation = null
                    this.$emit("change", this.chosenVariation);
                    this.sendProductSpecs();
                }
                if (spec || (! this.chosenVariation && this.specVariations.length)) {
                    for (let item in this.specVariations) {
                        if (this.specVariations.hasOwnProperty(item)) {
                            if (! this.specVariations[item].disabled_at) {
                                this.chosenVariation = this.specVariations[item].id;
                                this.$emit("change", this.chosenVariation);
                                this.sendProductSpecs();
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>