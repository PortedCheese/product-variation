<template>
    <form>
        <addon-variations :specifications="specifications" :variations="variations" v-model="chosenAddonVariation"></addon-variations>

        <div class="btn-group" role="group">
            <button v-if="!isAdded"
                type="button"
                    class="btn btn-sm me-2"
                    :class="isDisable ? 'btn-outline-secondary' : 'btn-outline-primary'"
                    data-toggle="modal"
                    :disabled="chosenAddonVariation === '' || loading || isAdded || isDisable || toCart"
                    @click="orderAddonVariation()">
                Добавить
            </button>
            <button v-if="isAdded"
                type="button"
                    class="btn btn-sm btn-outline-secondary"
                    data-toggle="modal"
                    :disabled="chosenAddonVariation === '' || loading || (! orderAddonVariations.length) || ! isAdded || toCart"
                    @click="removeAddonVariation()">
                Убрать
            </button>
        </div>

    </form>
</template>

<script>
import productVariationEventBus from '../category-product/categoryProductEventBus';
    import AddonVariations from "./ChooseAddonVariationComponent";

    export default {
        name: "OrderAddonComponent",

        components: {
            "addon-variations": AddonVariations
        },

        props: {
            variations: {
                type: Array,
                required: true
            },
            specifications:{
                type: Object,
                required: false
            }
        },

        data() {
            return {
                loading: false,
                chosenAddonVariation: "",
                orderAddonVariations: [],
                toCart: false,
                productSpecs: [],
                messages: [],
                errors: [],
            }
        },
        mounted() {
            productVariationEventBus.$on("change-choosing-product-spec", this.changeProductSpecs);
            productVariationEventBus.$on("remove-this-addon", this.removeThisAddon);
            this.$root.$on("change-cart", this.removeAllAddons)
            //productVariationEventBus.$on("change-choosing-product-variation", this.changeProductVariation);
        },

        computed: {

            variationData() {
                let variation = false;
                for (let item in this.variations) {
                    if (this.variations.hasOwnProperty(item)) {
                        if (this.variations[item].id === this.chosenAddonVariation) {
                            variation = this.variations[item];
                        }
                    }
                }
                return variation;
            },
            isAdded(){
                let flag =  false;
                for (let item of this.orderAddonVariations){
                    if (item.id === this.chosenAddonVariation){
                        flag = true;
                        break;
                    }
                }
                return flag;
            },
            isDisable() {
                return this.checkSpecValues(this.productSpecs, this.variationData.specifications ? this.variationData.specifications:[])
            }
        },

        methods: {
            removeAllAddons(){
                this.toCart = true
                if (! this.orderAddonVariations.length) return;
                for (let item of this.orderAddonVariations){
                    this.removeAddonVariation(item);
                }
            },
            removeThisAddon(data) {
                if (! this.orderAddonVariations.length) return;
                for (let item of this.orderAddonVariations){
                    if (data.id === item.id){
                        this.removeAddonVariation(data);
                        break;
                    }
                }

            },
            orderAddonVariation(){
                this.orderAddonVariations.push(this.variationData);
                this.loading = true;
                productVariationEventBus.$emit("change-order-addons", this.variationData);
                this.loading = false;
            },
            removeAddonVariation(addon){
                let id;
                if (! addon) addon = this.variationData
                id = addon.id
                let index = this.orderAddonVariations.indexOf(id);
                if (index){
                    if (this.orderAddonVariations.length === 1)
                        this.orderAddonVariations = [];
                    else
                        this.orderAddonVariations.splice(this.orderAddonVariations.indexOf(id),1);
                    this.loading = true;
                    productVariationEventBus.$emit("remove-order-addons",addon);
                    this.loading = false;
                }

            },
            checkSpecValues(pSpecs, addSpesc){
                let flag = 0;
                let count = 0;
                if (! pSpecs || pSpecs.length < 1) return false;
                for (let pSpec of pSpecs) {
                    if (! addSpesc || addSpesc.length < 1) return false;
                    for (let addSpec of addSpesc){
                        if (pSpec.specification_id === addSpec.specification_id )
                        {
                            count++;
                            if (pSpec.value === addSpec.value) {
                                flag ++;
                                break;
                            }
                        }
                    }
                }
                if (flag == count) return false
                else  return  true;
            },

            changeProductSpecs(data){
                this.productSpecs = data;
                if (this.toCart) this.toCart = false
                // remove addons has other specfication values
                if (this.orderAddonVariations.length < 1) return;
                for (let variation of  this.orderAddonVariations){
                    if (this.checkSpecValues(this.productSpecs, variation.specifications)){
                        this.orderAddonVariations.splice(this.orderAddonVariations.indexOf(variation),1);
                        productVariationEventBus.$emit("remove-order-addons", variation);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>