<template>
    <div class="card-header">
        <button type="button"
                class="btn btn-success"
                data-toggle="modal"
                data-target="#newVariationModal">
            Добавить вариацию
        </button>

        <div class="modal fade"
             id="newVariationModal"
             tabindex="-1"
             aria-labelledby="newVariationModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newVariationModalLabel">Добавить вариацию</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="alert alert-danger" role="alert" v-if="Object.keys(errors).length">
                                <template v-for="field in errors">
                                    <template v-for="error in field">
                                        <span>{{ error }}</span>
                                        <br>
                                    </template>
                                </template>
                            </div>

                            <add-new-variation-specification :available="this.available"
                                                             :specifications="this.specifications"
                                                             @returnSpecValues="getSpecValues"
                                                             @returnAddMode="setAddMode"
                                                             :addMode="true"
                                                             :reset-spec-values="this.resetSpecValues"
                                                             v-if="this.canAddSpecifications"
                            ></add-new-variation-specification>

                            <div class="form-group">
                                <a data-toggle="collapse" href="#collapseProductImages" role="button" aria-expanded="false">
                                    Изображение для вариации
                                </a>
                                <div class="collapse mt-3" id="collapseProductImages">
                                    <div class="card card-body">
                                        <div>
                                            <input  type="radio"
                                                    name="product_image_id"
                                                    id="image"
                                                    :checked="! product_image_id"
                                                    v-model="product_image_id">
                                            </input>
                                            <label for="image">
                                                Без изображения
                                            </label>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <div v-for="image in images" class="mr-2 mb-2">
                                                <input  type="radio"
                                                        name="product_image_id"
                                                        :id="'image'+image.id"
                                                        :value="image.id"
                                                        v-model="product_image_id">
                                                </input>
                                                <label :for="'image'+image.id">
                                                    <img :src="image.src" :alt="image.name" class="img-thumbnail">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sku">Артикул</label>
                                <input type="text"
                                       id="sku"
                                       name="sku"
                                       v-model="sku"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="measurement">Измерение</label>
                                <select class="custom-select"
                                    v-model="measurement"
                                    id="measurement"
                                    name="measurement">
                                    <option value="" selected>Выбрать</option>
                                    <option v-for="(item, index) in measurements" :value="item.id">{{ item.title  }} ({{ item.short}})</option>
                              </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Описание <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="description"
                                       name="description"
                                       required
                                       v-model="description"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="price">Цена <span class="text-danger">*</span></label>
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       id="price"
                                       name="price"
                                       required
                                       v-model="price"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="sale_price">Старая цена</label>
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       id="sale_price"
                                       name="sale_price"
                                       v-model="sale_price"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="sale"
                                           v-model="sale"
                                           name="sale">
                                    <label class="custom-control-label" for="sale">Действует скидка</label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="button"
                                @click="postNewVariation"
                                :disabled="loading || ! checkValues"
                                class="btn btn-primary">
                            Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AddNewVariationSpecification from "./AddProductVariationProductSpecificationComponent"
    export default {
        name: "AddProductVariationComponent",

        components: {
            "add-new-variation-specification": AddNewVariationSpecification,
        },

        props: {
            postUrl: {
                required: true,
                type: String
            },
            measurements: {
              required: true,
              type: Array
            },
            available: {
                required: false,
                type: Array,
            },
            specifications: {
                required: false,
            },
            canAddSpecifications: {
                required: false
            },
            images:{
                required: true,
                type: Array
            }
        },

        data() {
            return {
                loading: false,
                errors: [],
                sku: "",
                description: "",
                price: 0,
                sale_price: 0,
                sale: false,
                measurement: "",
                product_image_id: null,
                specValues: [],
                specIds: [],
                resetSpecValues: false,
            }
        },

        computed: {
            checkValues() {
                let result = true;
                if (! this.description.length) {
                    result = false;
                }
                return result;
            },
        },

        methods: {
            // Сброс после создания новой вариации
            setAddMode(data){
                if (data["addMode"])
                    this.resetSpecValues = false;
            },
            getSpecValues(data){
                this.specValues = data["specValues"];
                for (let i in this.specValues ){
                    this.specIds[i] = this.specValues[i].product_specification_id;
                }
            },

            postNewVariation() {
                this.loading = true;
                this.errors = [];
                axios
                    .post(this.postUrl, {
                        sku: this.sku,
                        description: this.description,
                        price: this.price,
                        sale_price: this.sale_price,
                        sale: this.sale ? 1 : 0,
                        measurement: this.measurement,
                        specificationIds: this.specIds,
                        image_id: this.product_image_id
                    })
                    .then(response => {
                        let data = response.data;
                        if (data.success) {
                            $("#newVariationModal").modal("hide");
                            Swal.fire({
                                position: "top-end",
                                type: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            this.$emit("add-new-variation");
                            this.sku = "";
                            this.description = "";
                            this.price = 0;
                            this.sale_price = 0;
                            this.sale = false;
                            this.specIds = [];
                            this.specValues = [];
                            this.measurement = "";
                            this.resetSpecValues = true;
                            this.product_image_id = null;
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        if (data.hasOwnProperty("errors")) {
                            this.errors = data.errors;
                        }
                        else {
                            Swal.fire({
                                type: "error",
                                title: "Упс...",
                                text: "Что то пошло не так",
                                footer: data.message
                            })
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },

        }
    }
</script>

<style scoped>

</style>