<template>
    <form>
        <product-variations :variations="variations" v-model="chosenVariation"></product-variations>

        <div class="btn-group"
             role="group">
            <button type="button"
                    class="btn btn-primary"
                    data-toggle="modal"
                    :disabled="chosenVariation === '' || loading"
                    data-target="#orderProduct">
                Заказать
            </button>
        </div>

        <div class="modal" id="orderProduct" aria-labelledby="orderProductLabel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderProductLabel">
                            Заказать товар <span v-if="variationData">{{ variationData.description }}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="orderProductForm">
                            <div class="alert alert-danger" role="alert" v-if="Object.keys(errors).length">
                                <template v-for="field in errors">
                                    <template v-for="error in field">
                                        <span>{{ error }}</span>
                                        <br>
                                    </template>
                                </template>
                            </div>
                            <div class="alert alert-success" role="alert" v-if="Object.keys(messages).length">
                                <template v-for="message in messages">
                                    <span>{{ message }}</span>
                                    <br>
                                </template>
                            </div>

                            <div class="form-group">
                                <label for="name">Имя</label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       v-model="formData.name"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       v-model="formData.email"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text"
                                       id="phone"
                                       name="phone"
                                       v-model="formData.phone"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="comment">Комментарий</label>
                                <textarea class="form-control"
                                          v-model="formData.comment"
                                          name="comment"
                                          id="comment"
                                          rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           checked
                                           v-model="formData.privacy_policy"
                                           name="privacy_policy"
                                           class="custom-control-input"
                                           id="privacy_policy">
                                    <label class="custom-control-label" for="privacy_policy">
                                        <span v-if="policyUrl">Согласие с "<a :href="policyUrl" target="_blank">Политикой конфиденциальности</a>"</span>
                                        <span v-else>Согласие с "Политикой конфиденциальности"</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">
                            Отмена
                        </button>
                        <button type="button"
                                @click="sendOrder"
                                :disabled="! orderAvailable || loading"
                                class="btn btn-primary">
                            Заказать <i class="fas fa-spinner fa-spin" v-if="loading"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import Variations from "./ChooseProductVariationComponent";

    export default {
        name: "OrderSingleProductComponent",

        components: {
            "product-variations": Variations
        },

        props: {
            variations: {
                type: Array,
                required: true
            },
            user: {
                type: Object|Boolean,
                required: true
            },
            policyUrl: {
                type: String|Boolean,
                required: true
            }
        },

        data() {
            return {
                loading: false,
                chosenVariation: "",
                messages: [],
                errors: [],
                formData: {}
            }
        },

        created() {
            this.initFormData();
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

            orderAvailable() {
                if (! this.variationData) {
                    return false;
                }
                if (! this.formData.email.length && ! this.formData.phone.length) {
                    return false;
                }
                if (! this.formData.name.length) {
                    return false;
                }
                return this.formData.privacy_policy;
            }
        },

        methods: {
            initFormData() {
                this.formData = {
                    name: "",
                    email: "",
                    phone: "",
                    comment: "",
                    privacy_policy: true
                };
                if (this.user) {
                    this.formData.name = this.user.name;
                    this.formData.email = this.user.email;
                    this.formData.phone = this.user.phone;
                }
            },
            sendOrder() {
                this.loading = true;
                this.errors = [];
                this.messages = [];
                axios
                    .put(this.variationData.orderSingleUrl, this.formData)
                    .then(response => {
                        let result = response.data;
                        if (result.success) {
                            $("#orderProductForm").trigger("reset");
                            this.initFormData();
                            $("#newVariationModal").modal("hide");
                            this.messages.push(result.message);
                        }
                        else {
                            this.errors.push([result.message]);
                        }

                    })
                    .catch(error => {
                        let data = error.response.data;
                        if (data.hasOwnProperty("errors")) {
                            this.errors = data.errors;
                        }
                        else {
                            this.errors.push([data.message]);
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