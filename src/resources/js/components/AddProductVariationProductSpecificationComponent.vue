<template>
    <div class="form-group">
        <a data-toggle="collapse" href="#collapseProductSpecifications" role="button" aria-expanded="false" aria-controls="collapseExample">
            Характеристики
        </a>
        <div class="collapse" id="collapseProductSpecifications">
            <div class="card card-body">
                <div class="input-group mb-3">
                    <select name="spec"
                            id="spec"
                            v-model="chosenSpecId"
                            class="form-control custom-select">
                        <option value="">Выберите...</option>
                        <option v-for="item in available" :value="item.id"
                                :disabled="disableCurrentSpec(item.id, item.title)">
                            {{ item.title }}
                        </option>
                    </select>
                    <div class="input-group-append" v-if="chosenSpec">
                        <select v-if="chosenSpec"
                                class="form-control custom-select"
                                name="valuesList"
                                :id="'value'+chosenSpecId"
                                v-model="chosenValueId">
                            <option v-for="(option, index) in specifications[chosenSpecId]"
                                    :value="option.id"
                                >
                                {{ option.value }} {{ option.code ? "["+option.code+"]": "" }}
                            </option>
                        </select>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-success"
                                :disabled="! chosenValueId"
                                @click="addNewValue"
                                type="button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="input-group mb-3" v-for="(item, index) in getValues" :key="index">
                    <input type="text"
                           readonly
                           name="title"
                           v-model="item.title"
                           class="form-control"
                           placeholder="Характеристика"
                           aria-label="Характеристика">
                    <input v-model="item.value"
                           type="text"
                           readonly
                           name="value"
                           class="form-control"
                           placeholder="Значение"
                           aria-label="Значение">
                    <input v-if="item.code"
                           v-model="item.code"
                           type="text"
                           readonly
                           name="value"
                           class="form-control"
                           placeholder="Код"
                           aria-label="Код">
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger"
                                @click="removeValue(index)"
                                type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>

                </div>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AddProductVariationProductSpecificationComponent",

        props: {
            available: {
                required: false,
                type: Array,
            },
            specifications: {
               required: false,
            },
            variationSpecs: {
               required: false,
            },
            resetSpecValues: {
                required: false
            },
            addMode:{
                required: false
            }
        },

        data() {
            return {
                loading: false,
                errors: [],
                chosenSpecId: "",
                chosenValueId: "",
                specValues: [],
            }
        },

        computed: {
            // заполнить характеристики из вариации
            getValues(){
                // сброс характеристик при создании новой вариации
                if (this.addMode && this.resetSpecValues){
                    this.specValues = [];
                    this.$emit('returnAddMode', {
                        addMode: true
                    })
                }
                // сброс и заполнение характеристик при создании новой вариации
                if (this.variationSpecs && this.resetSpecValues){
                    this.specValues = [];
                    for (let spec of this.variationSpecs){
                        this.specValues.push({
                            specification_id: spec.specification_id,
                            product_specification_id: spec.id,
                            title: spec.title,
                            value: spec.value,
                            code: spec.code ? spec.code : ""
                        })
                    }
                    this.$emit('returnEditMode', {
                        editMode: true,
                        specValues: this.specValues
                    })
                }
                return this.specValues

            },
            // выбранная характеристика
            chosenSpec() {
                let choose = false;
                for (let item in this.available) {
                    if (this.available.hasOwnProperty(item)) {
                        let id = this.available[item].id;
                        if (id === this.chosenSpecId) {
                            choose = this.available[item];
                        }
                    }
                }
                return choose;
            },
            // выбранное значение характеристики
            chosenValue() {
                let choose = false;
                for (let item in this.specifications[this.chosenSpecId])
                {
                    if (this.specifications[this.chosenSpecId].hasOwnProperty(item)) {
                        let id = this.specifications[this.chosenSpecId][item].id;
                        if (id == this.chosenValueId) {
                            choose = this.specifications[this.chosenSpecId][item];
                            break;
                        }
                    }
                }
                return choose;
            }
        },

        methods: {
            created(){
                this.$parent.$on("add-new-variation", this.clear());
                this.$parent.$on("update-variation", this.clear());
            },
            updated(){
                this.$parent.$on("add-new-variation", this.clear());
                this.$parent.$on("update-variation", this.clear());
            },

            // очистка выбранных характеристик после добавления/изменения
            clear(){
                for (let index in  this.specValues)
                    this.removeValue(index)
                this.chosenSpecId = "";
                this.chosenValueId = false;
            },
            // Отключить уже выбранные характеристики
            disableCurrentSpec(optionId, optionTitle){
                let disable = false;
                for (let item of this.specValues){
                    if (optionId == item.specification_id)
                    {
                        disable = true;
                        break;
                    }
                }
                return disable;
            },
            // Добавить новое значение характеристики в ввариацию
            addNewValue() {
                this.canAddNewValue = true;

                this.specValues.push({
                    specification_id: this.chosenSpecId,
                    product_specification_id: this.chosenValueId,
                    title: this.chosenSpec.title,
                    value: this.chosenValue.value,
                    code: this.chosenValue.code ? this.chosenValue.code : ""
                });
                this.chosenSpecId = "";
                this.chosenValueId = false;
                this.sendSpecValues();

            },
            // Удалить значение.
            removeValue(index) {
                this.specValues.splice(index, 1);
                this.sendSpecValues();
            },
            // Передать характеристики родителю
            sendSpecValues(){
                this.$emit('returnSpecValues', {
                    specValues: this.specValues
                })
            }
        }
    }
</script>

<style scoped>

</style>