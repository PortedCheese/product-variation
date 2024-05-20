<template>
    <div class="modal fade" id="editVariationModal" tabindex="-1" aria-labelledby="editVariationModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariationModalLabel">Редактировать вариацию</h5>
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
                                                         :variation-specs="this.variation.specifications"
                                                         @returnSpecValues="getSpecValues"
                                                         @returnEditMode="setEditMode"
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
                                                :checked="! variation.product_image_id"
                                                v-model="variation.product_image_id">
                                        </input>
                                        <label for="image" class="form-check-label" >
                                            Без изображения
                                        </label>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div v-for="image in images" class="mr-2 mb-2">
                                            <input  type="radio"
                                                    name="product_image_id"
                                                    :id="'image'+image.id"
                                                    :value="image.id"
                                                    :checked="variation.product_image_id && variation.product_image_id === image.id"
                                                    v-model="variation.product_image_id">
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
                            <label for="edit-sku">Артикул</label>
                            <input type="text"
                                   id="edit-sku"
                                   name="sku"
                                   v-model="variation.sku"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="measurement">Измерение</label>
                            <select class="custom-select"
                                  v-model="variation.measurement"
                                  id="measurement"
                                  name="measurement">
                                <option value="" selected>Не указано</option>
                                <option v-for="(item) in measurements" :value="item.id">
                                  {{ item.title  }} ({{ item.short }})
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit-description">Описание <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="edit-description"
                                   name="description"
                                   required
                                   v-model="variation.description"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="edit-price">Цена <span class="text-danger">*</span></label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   id="edit-price"
                                   name="price"
                                   required
                                   v-model="variation.price"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="edit-sale_price">Старая цена</label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   id="edit-sale_price"
                                   name="sale_price"
                                   v-model="variation.sale_price"
                                   class="form-control">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="edit-sale"
                                       v-model="variation.sale"
                                       name="sale">
                                <label class="custom-control-label" for="edit-sale">Действует скидка</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button"
                            class="btn btn-primary"
                            @click="updateVariation"
                            :disabled="loading">
                        Обновить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AddNewVariationSpecification from "./AddProductVariationProductSpecificationComponent"
    export default {
        name: "EditProductVariationComponent",

        components: {
            "add-new-variation-specification": AddNewVariationSpecification,
        },

        props: {
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
                variation: {},
                errors: [],
                loading: false,
                specValues: [],
                specIds: [],
                resetSpecValues: false,
            }
        },

        created() {
            this.$parent.$on("edit-show", this.initEditable);
        },

        methods: {
            //  характеристики для редактирования вариации переданы
            setEditMode(data){
              if (data["editMode"])
                  this.resetSpecValues = false;
              if (data["specValues"])
                  this.getSpecValues(data)
            },
            getSpecValues(data){
                this.specValues = data["specValues"];
                if (!this.specValues.length ) this.specIds = []
                for (let i in this.specValues ){
                    this.specIds[i] = this.specValues[i].product_specification_id;
                }
            },

            initEditable(variation) {
                this.variation = Object.assign({}, variation);
                this.variation.sale = !!this.variation.sale;
                $("#editVariationModal").modal("show");
                this.resetSpecValues  = true;
            },

            updateVariation() {
                this.loading = true;
                this.errors = [];
                axios
                    .put(this.variation.updateUrl, {
                        sku: this.variation.sku,
                        description: this.variation.description,
                        price: this.variation.price,
                        sale_price: this.variation.sale_price,
                        sale: this.variation.sale ? 1 : 0,
                        measurement: this.variation.measurement,
                        specificationIds: this.specIds,
                        image_id: this.variation.product_image_id
                    })
                    .then(response => {
                        let data = response.data;
                        if (data.success) {
                            $("#editVariationModal").modal("hide");
                            Swal.fire({
                                position: "top-end",
                                type: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            this.$emit("update-variation");
                            this.specIds = [];
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
            }
        }
    }
</script>

<style scoped>

</style>