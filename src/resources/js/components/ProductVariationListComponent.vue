<template>
    <div class="col-12 mb-2">
        <div class="card">
            <add-new :post-url="postUrl" :measurements="measurements" v-on:add-new-variation="getList" v-if="canCreate"></add-new>
            <div class="card-header" v-else>
                <h5 class="card-title">Вариации</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Измерение</th>
                            <th>Цена</th>
                            <th>Старая цена</th>
                            <th>Скидка</th>
                            <th>Описание</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in variations">
                            <td>{{ item.sku }}</td>
                            <td>{{ item.full_measurement }}</td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.sale_price }}</td>
                            <td>{{ item.sale ? "Да" : "Нет" }}</td>
                            <td>{{ item.description }}</td>
                            <td>
                                <div role="toolbar" class="btn-toolbar">
                                    <div class="btn-group mr-1">
                                        <button type="button"
                                                v-if="item.updateUrl"
                                                @click="editVariation(item)"
                                                :disabled="loading"
                                                class="btn btn-primary">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button type="button"
                                                v-if="item.deleteUrl"
                                                @click="deleteVariation(item)"
                                                :disabled="loading"
                                                class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group" v-if="item.disableUrl">
                                        <button type="button"
                                                class="btn"
                                                @click="disableVariation(item)"
                                                :class="'btn-' + (item.disabled_at ? 'secondary' : 'success')">
                                            <i class="fas" :class="'fa-toggle-' + (item.disabled_at ? 'off' : 'on')"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <edit-form v-on:update-variation="getList" :measurements="measurements"></edit-form>
    </div>
</template>

<script>
    import AddNewVariation from "./AddProductVariationComponent"
    import EditVariation from "./EditProductVariationComponent"
    export default {
        name: "ProductVariationListComponent",

        components: {
            "add-new": AddNewVariation,
            "edit-form": EditVariation
        },

        props: {
            getUrl: {
                required: true,
                type: String
            },
            postUrl: {
                required: true,
                type: String
            },
            canCreate: {
                required: true,
                type: Number
            },
            measurements: {
              required: true,
              type: Array
            }
        },

        data() {
            return {
                loading: false,
                variations: [],
                errors: [],
            }
        },

        created() {
            this.getList();
        },

        methods: {
            getList() {
                this.loading = true;
                axios
                    .get(this.getUrl)
                    .then(response => {
                        let data = response.data;
                        this.variations = data.items;
                    })
                    .catch(error => {
                        let data = error.response.data;
                        Swal.fire({
                            type: "error",
                            title: "Упс...",
                            text: "Что то пошло не так",
                            footer: data.message
                        })
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },

            editVariation(variation) {
                this.$emit("edit-show", variation);
            },

            disableVariation(variation) {
                Swal.fire({
                    title: "Вы уверены?",
                    text: "Это изменит статус доступности вариации",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Да, изменить!",
                    cancelButtonText: "Отмена",
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        this.errors = [];
                        axios
                            .put(variation.disableUrl)
                            .then(response => {
                                let data = response.data;
                                if (data.success) {
                                    this.getList();
                                    Swal.fire({
                                        position: "top-end",
                                        type: "success",
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
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
                })
            },

            deleteVariation(variation) {
                Swal.fire({
                    title: "Вы уверены?",
                    text: "Это действие будет невозможно отменить",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Да, удалить!",
                    cancelButtonText: "Отмена",
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        this.errors = [];
                        axios
                            .delete(variation.deleteUrl)
                            .then(response => {
                                let data = response.data;
                                if (data.success) {
                                    this.getList();
                                    Swal.fire({
                                        position: "top-end",
                                        type: "success",
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
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
                });
            }
        }
    }
</script>

<style scoped>

</style>