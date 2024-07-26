<template>
    <div v-if="this.available && this.variations.length">
        <a v-if="!this.fullMode && this.variations" href="#" role="button"
           :id="'#dropdownTeaser'+this.variations[0].product_id"
           data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
            Доступные параметры
        </a>
        <div :class="!this.fullMode && this.variations? 'dropdown-menu':'form-row' "
             :aria-labelledby="'dropdownTeaser'+this.variations[0].product_id">
            <input name type="hidden" id="specInput" v-model="chosenSpec">
            <div class="form-group col-12" v-for="(item, index) in this.available">
                <label><small>{{  index }}</small></label>
                <button v-if="variations && variations.length >1 && fullMode & !addonMode"
                        type="button"
                        class="close"
                        @click="resetChooseSpec(item[0].specification_id)">&times;</button>
                <br>
                <template v-for="obj in item">
                    <button v-if="" type="button"
                            class="btn btn-sm mr-2 mb-2"
                            :class="checkPrimaryActive(obj)"
                            :disabled="checkAvailable(obj)"
                            @click="btnClick(obj)"
                    >
                        <span v-if="obj.code" class="badge" :style="{ backgroundColor: obj.code }">&nbsp;</span>
                        <span v-if="fullMode || !obj.code">{{ obj.value }}</span>
                    </button>
                </template>

            </div>

        </div>
    </div>
</template>

<script>
import productVariationEventBus from '../category-product/categoryProductEventBus';
    export default {
        name: "ChooseSpecificationComponent",

        props: {
            available: {
                type: Object,
                required: true
            },

            chose: {
                type: Array,
                required: false
            },
            variations: {
                type: Array,
                required: false
            },
            current:{
                type: Array,
                required: false
            },
            fullMode: {
                type: Boolean,
                required: true
            },
            addonMode: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                chosenSpec: [],
                productSpecs: []
            }
        },

        created() {
            if (this.current){
                for (let spec of this.current){
                    this.chosenSpec.push(spec);
                }
            }

            this.changeChose();
            this.$emit("changeChoosing", {spec: this.chosenSpec, addonMode: this.addonMode} );

            // first load: send request to get product spec (to ChooseProductVatiation)
            if (this.addonMode){
                productVariationEventBus.$emit("get-choosing-product-spec");
            }

        },

        mounted() {
            // while choosen product variation changed: get product specs (from ChooseProductVatiation)
            if (this.addonMode){
                productVariationEventBus.$on("change-choosing-product-spec", this.hideOtherSpec);
            }
        },

        watch: {
            chose: function() {
                this.changeChose();
            }
        },


        methods: {
            resetChooseSpec(spec){
                for (let index in this.chosenSpec){
                    if (! this.chosenSpec.hasOwnProperty(index)) continue;
                    if (this.chosenSpec[index].specification_id === spec){
                        this.chosenSpec.splice(index,1);
                    }
                }
                this.$emit("changeChoosing", {spec: this.chosenSpec, addonMode: this.addonMode} );
            },
            changeChose() {
                if (this.chose.hasOwnProperty("spec"))
                    this.chosenSpec = this.chose.spec;
            },
            hideOtherSpec(data){
                this.productSpecs = data;
            },
            checkAvailable(obj){
                let disable = true;
                // количество выбранных значений (без текущей характеристики)
                let length = this.chosenSpec.length;
                if (length > 0){
                    for (let chSpec of this.chosenSpec){
                        if (chSpec.specification_id === obj.specification_id){
                            length --;
                            break;
                        }
                    }
                }
                // ищем вариацию с выбранными и текущей характеристикой
                for (let variation of this.variations){
                    // количество совпавших с выбранными характеристик
                    let count = 0;
                    // совпавшее значение с текущей характеристикой
                    let objSpec = false;
                    if (variation.specifications && ! variation.disabled_at && disable) {
                        for (let spec of variation.specifications){
                            for (let chSpec of this.chosenSpec){
                                // сравниваем значения характеристик, отличных от текущей кнопки
                                if (spec.specification_id !== obj.specification_id && chSpec.id === spec.id )
                                {
                                    count++;
                                    break;
                                }
                            }
                            // сравниваем значение с текущей кнопкой
                            if (obj.id === spec.id)
                                objSpec = true;
                        }
                    }

                    if (count === length && objSpec) {
                        disable = false;
                        break;
                    }
                }
                //
                // if (this.addonMode) {
                //     for (let spec of this.productSpecs){
                //         if (obj.specification_id == spec.specification_id && obj.value !== spec.value){
                //             disable = true;
                //             break;
                //         }
                //
                //     }
                // }
                return disable;
            },

            checkPrimaryActive(obj){
                let btnClass = "btn-outline-primary";
                if (this.addonMode) {
                    for (let spec of this.productSpecs){
                        if (obj.specification_id == spec.specification_id && obj.value !== spec.value){
                            btnClass = "btn-outline-secondary";
                            break;
                        }

                    }
                }

                for (let index in this.chosenSpec){
                    if (! this.chosenSpec.hasOwnProperty(index)) continue;
                    if (this.chosenSpec[index].specification_id === obj.specification_id && this.chosenSpec[index].id=== obj.id){
                        btnClass += " active";
                    }
                }

                return btnClass;
            },

            btnClick(obj) {
                let selected = false;
                if (this.chosenSpec){
                    for (let index in this.chosenSpec){
                        if (! this.chosenSpec.hasOwnProperty(index)) continue;
                        if (this.chosenSpec[index].specification_id === obj.specification_id && this.chosenSpec[index].id === obj.id){
                            selected = true;
                            break;
                        }
                        if (this.chosenSpec[index].specification_id === obj.specification_id){
                            selected = true;
                            this.chosenSpec.splice(index, 1, obj);
                            break;
                        }
                    }
                }
                if (! selected) this.chosenSpec.push(obj);
                this.$emit("changeChoosing", {spec: this.chosenSpec, addonMode: this.addonMode} );
            }
        }
    }
</script>

<style scoped>

</style>