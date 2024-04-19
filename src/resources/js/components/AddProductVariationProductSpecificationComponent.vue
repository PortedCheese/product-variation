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

                <div class="input-group mb-3" @loadeddata="clear" v-for="(item, index) in specValues" :key="index">
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
            resetSpecValues:{
                required: false,
            },
            available: {
                required: false,
                type: Array,
            },
           specifications: {
               required: false,
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
                this.clear();
            },
            updated(){
                this.clear();
            },
            clear(){
                if (this.resetSpecValues)
                {
                    for (let index in  this.specValues)
                        this.removeValue(index)
                    this.chosenSpecId = "";
                    this.chosenValueId = false;
                }
            },
            // Отключить выбранные характеристики
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
            // Добавить новое значение в вариацию
            addNewValue() {
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
                this.$emit('updateParent', {
                    specValues: this.specValues
                })
            }
        }
    }
</script>

<style scoped>

</style>