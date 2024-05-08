<template>
    <div class="form-row" v-if="this.available">
        <input name type="hidden" id="specInput" v-model="chosenSpec">
        <div class="form-group col-12" v-for="(item, index) in this.available">
            <label @click="resetChooseSpec(item[0].specification_id)" ><small>{{  index }}</small></label>
            <br>
            <button type="button"
                    class="btn btn-sm btn-outline-primary mr-2 mb-2"
                    :class="checkActive(obj) ? 'active' : ''"
                    :disabled="checkAvailable(obj)"
                    @click="btnClick(obj)"
                    v-for="obj in item">
                <span v-if="obj.code" class="badge" :style="{ backgroundColor: obj.code }">&nbsp;</span>
                {{ obj.value }}
            </button>
            <button v-if="variations && variations.length >1" type="button" class="close"  @click="resetChooseSpec(item[0].specification_id)">&times;</button>
        </div>
    </div>
</template>

<script>
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
            }
        },

        data() {
            return {
                chosenSpec: [],
            }
        },

        created() {
            //this.$parent.$on("change",  this.setActive());

            for (let spec of this.current){
                this.chosenSpec.push(spec);
            }
            this.changeChose();
            this.$emit("changeChoosing", {spec: this.chosenSpec} );
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
                this.$emit("changeChoosing", {spec: this.chosenSpec} );
            },
            changeChose() {
                if (this.chose.hasOwnProperty("spec"))
                    this.chosenSpec = this.chose.spec;
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
                return disable;
            },

            checkActive(obj){
                for (let index in this.chosenSpec){
                    if (! this.chosenSpec.hasOwnProperty(index)) continue;
                    if (this.chosenSpec[index].specification_id === obj.specification_id && this.chosenSpec[index].id=== obj.id){
                        return true;
                    }
                }
                return false;
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
                this.$emit("changeChoosing", {spec: this.chosenSpec} );
            }
        }
    }
</script>

<style scoped>

</style>