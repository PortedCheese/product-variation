<template>
    <div class="col-12 mt-2">
        <div class="card">
            <add-new :post-url="postUrl" v-on:add-new-variation="getList"></add-new>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Цена</th>
                            <th>Старая цена</th>
                            <th>Скидка</th>
                            <th>Описание</th>
                            <th>Доступно</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in variations">
                            <td>{{ item.sku }}</td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.sale_price }}</td>
                            <td>{{ item.sale ? "Да" : "Нет" }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.disabled_at ? "Нет" : "Да" }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" @click="editVariation(item)" :disabled="loading" class="btn btn-primary">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" @click="deleteVariation(item)" :disabled="loading" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <edit-form v-on:update-variation="getList"></edit-form>
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