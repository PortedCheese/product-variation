<template>
    <form>
        <product-variations :specifications="specifications" :variations="variations" v-model="chosenVariation" :page-mode="true"></product-variations>

        <div class="btn-group"
             role="group">
            <button type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    :disabled="chosenVariation === '' || loading"
                    data-bs-target="#orderProduct">
                Заказать
            </button>
        </div>

        <div class="modal" id="orderProduct" aria-labelledby="orderProductLabel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex flex-column" id="orderProductLabel">
                            <span>Заказать товар <span v-if="variationData">{{ variationData.description }}</span></span>
                            <span class="small text-muted" v-if="variationData.specifications">
                                <span class="me-2" v-for="(item,index) in variationData.specifications">
                                    {{ item.title }}: {{ item.value }}
                                </span>
                            </span>
                        </h5>
                        <button type="button" @click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                                <p v-if="Object.keys(addonVariations).length">Дополнения:</p>
                                <div class="d-flex flex-row flex-wrap">
                                    <div class="alert alert-light alert-dismissible fade show me-2 mb-2" v-for="(item,index) in addonVariations">
                                        <div class="card-header p-1">
                                            {{ item.description }}
                                          <button type="button" class="btn-close" @click="removeThisAddon(item)" data-bs-dismiss="alert" aria-label="Close">
                                          </button>
                                        </div>
                                        <div class="card-body p-1">
                                            <p class="mb-2">{{ item.human_price }}
                                                <svg class="rub-format__ico">
                                                    <use xlink:href="#catalog-rub"></use>
                                                </svg>
                                                {{ item.short_measurement }}
                                            </p>
                                            <p class="mb-2 text-muted">
                                                <template v-for="(spec) in item.specifications">{{ spec.title }} : {{ spec.value }}</template>
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
                                  Я даю свое
                                  <a href="#agreementModal" data-bs-toggle="modal" data-bs-target="#agreementModal">Согласие на обработку персональных данных</a> и принимаю условия
                                    <a v-if="policyUrl" :href="policyUrl" target="_blank">Политики по обработке персональных данных</a>
                                    <span v-else>Политики по обработке персональных данных</span>
                                  </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="closeModal"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            <template v-if="! this.messages.length">Отмена</template><template v-if="this.messages.length">Закрыть</template>
                        </button>
                        <button v-if="! this.messages.length && ! this.errors.length" type="button"
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
    import productVariationEventBus from '../category-product/categoryProductEventBus';
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
            },
            specifications:{
                type: Object,
                required: false
            }
        },

        data() {
            return {
                loading: false,
                chosenVariation: "",
                addonVariations: [],
                messages: [],
                errors: [],
                formData: {},
            }
        },

        created() {
            this.initFormData();
        },

        mounted() {
            productVariationEventBus.$on("change-order-addons", this.changeOrderAddonsData);
            productVariationEventBus.$on("remove-order-addons", this.removeOrderAddonsData);
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
            removeThisAddon(data){
                productVariationEventBus.$emit("remove-this-addon", data);
            },
            changeOrderAddonsData(data){
                this.addonVariations.push(data);
            },
            removeOrderAddonsData(data){
                this.addonVariations.splice(this.addonVariations.indexOf(data),1);
            },
            initFormData() {
                this.messages = [];
                this.errors = [];
                this.formData = {
                    name: "",
                    email: "",
                    phone: "",
                    comment: "",
                    addons: [],
                    privacy_policy: true
                };
                if (this.user) {
                    this.formData.name = this.user.name;
                    this.formData.email = this.user.email;
                    this.formData.phone = this.user.phone;
                }
            },
            closeModal(){
                this.errors = [];
                this.messages = [];
            },
            sendOrder() {
                this.loading = true;
                this.errors = [];
                this.messages = [];
                for (let el of this.addonVariations){
                    this.formData.addons.push(el.id);
                }
                axios
                    .put(this.variationData.orderSingleUrl, this.formData)
                    .then(response => {
                        let result = response.data;
                        if (result.success) {
                            $("#orderProductForm").trigger("reset");
                            this.initFormData();
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