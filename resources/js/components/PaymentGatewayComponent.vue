<template>
    <div class="row">
        <div class="col-md-3">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Payment Gateways</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="bkash-tab" data-bs-toggle="pill" href="#bkash-content" role="tab" aria-controls="bkash-content" aria-selected="true">Bkash</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="stripe-tab" data-bs-toggle="pill" href="#stripe-content" role="tab" aria-controls="stripe-content" aria-selected="true">Stripe</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="offline-tab" data-bs-toggle="pill" href="#offline-content" role="tab" aria-controls="offline-content" aria-selected="true">Offline</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="tab-content p-0" id="v-pills-tabContent">
                <!--Bkash-->
                <div class="tab-pane fade show active" id="bkash-content" role="tabpanel" aria-labelledby="bkash-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Bkash</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <img style="width: auto;" :src="'/assets/img/icons/payment/payment_gateway_bkash.png'">
                                        <p><span class="text-danger">Bangladesh Only</span><br> To get credentials, visit here <a href="https://www.bkash.com/" target="_blank">https://www.bkash.com/</a> </p>

                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active">Active Status</label>
                                        <div class="col-sm-9">
                                            <input v-model="bkash.is_active" class="form-check-input mt-2" type="checkbox" value="1" id="is_active_bkash">
                                            <div v-if="errors.bkash.is_active" class="form-text text-danger"> {{ errors.bkash.is_active[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="bkash_app_key">App Key</label>
                                        <div class="col-sm-9">
                                            <input v-model="bkash.app_key" :class="errors.bkash.app_key ? 'is-invalid' : ''" type="text" class="form-control" id="bkash_app_key" placeholder="App key">
                                            <div v-if="errors.bkash.app_key" class="form-text text-danger"> {{ errors.bkash.app_key[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="bkash_app_secret">App Secret</label>
                                        <div class="col-sm-9">
                                            <input v-model="bkash.app_secret" :class="errors.bkash.app_secret ? 'is-invalid' : ''" type="text" class="form-control" id="bkash_app_secret" placeholder="App secret">
                                            <div v-if="errors.bkash.app_secret" class="form-text text-danger"> {{ errors.bkash.app_secret[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="bkash_username">Username</label>
                                        <div class="col-sm-9">
                                            <input v-model="bkash.username" :class="errors.bkash.username ? 'is-invalid' : ''" type="text" class="form-control" id="bkash_username" placeholder="Username">
                                            <div v-if="errors.bkash.username" class="form-text text-danger"> {{ errors.bkash.username[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="bkash_password">Password</label>
                                        <div class="col-sm-9">
                                            <input v-model="bkash.password" :class="errors.bkash.password ? 'is-invalid' : ''" type="text" class="form-control" id="bkash_password" placeholder="Password">
                                            <div v-if="errors.bkash.password" class="form-text text-danger"> {{ errors.bkash.password[0] }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('bkash')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Stripe-->
                <div class="tab-pane fade" id="stripe-content" role="tabpanel" aria-labelledby="stripe-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Stripe</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <img style="width: auto;" :src="'/assets/img/icons/payment/payment_gateway_stripe.png'">
                                        <p class="mt-3"><span class="text-danger">To get credentials, visit here </span><a href="https://www.stripe.com/" target="_blank">https://www.stripe.com/</a> </p>

                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active">Active Status</label>
                                        <div class="col-sm-9">
                                            <input v-model="stripe.is_active" class="form-check-input mt-2" type="checkbox" value="1" id="is_active_bkash">
                                            <div v-if="errors.stripe.is_active" class="form-text text-danger"> {{ errors.stripe.is_active[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="stripe_secret">Secret</label>
                                        <div class="col-sm-9">
                                            <input v-model="stripe.stripe_secret" :class="errors.stripe.stripe_secret ? 'is-invalid' : ''" type="text" class="form-control" id="stripe_secret" placeholder="Stripe secret">
                                            <div v-if="errors.stripe.stripe_secret" class="form-text text-danger"> {{ errors.stripe.stripe_secret[0] }} </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('stripe')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Bank-->
                <div class="tab-pane fade" id="offline-content" role="tabpanel" aria-labelledby="offline-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Offline</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <img style="width: auto;" :src="'/assets/img/icons/payment/payment_gateway_offline.png'">
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active_offline">Active Status</label>
                                        <div class="col-sm-9">
                                            <input v-model="offline.is_active" class="form-check-input mt-2" type="checkbox" value="1" id="is_active_offline">
                                            <div v-if="errors.offline.is_active" class="form-text text-danger"> {{ errors.offline.is_active[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="offline_message">Message</label>
                                        <div class="col-sm-9">
                                            <textarea v-model="offline.message" :class="errors.offline.message ? 'is-invalid' : ''" type="text" class="form-control" id="offline_message" placeholder="Company bank accounts or others">

                                            </textarea>
                                            <div v-if="errors.offline.message" class="form-text text-danger"> {{ errors.offline.message[0] }} </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('offline')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>


export default {
    name: "PaymentGatewayComponent",
    props: {},
    data: () => {
        return {
            prefix : 'payment_gateway',
            bkash : {},
            stripe : {},
            offline : {},
            errors : {
                bkash : {},
                stripe : {},
                offline : {}
            }
        }
    },
    mounted: function () {
        this.fetchAll();
    },
    methods: {
        fetchAll: function () {
            const self = this;
            loading();
            axios.get(BASE_URL + '/admin/settings/' + this.prefix + '/data')
                .then((response) => {
                    let data = response.data;
                    let prefix = self.prefix + '_';
                    for (const key in data) {
                        if (data.hasOwnProperty(key)) { // Check if the property is not inherited
                            let k = key.replace(prefix, '');
                            self[k] = data[key];
                        }
                    }
                })
                .catch(error => {
                    notify('Data fetch error', 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },

        clearErrors: function () {
            this.errors = {
                bkash : {},
                stripe : {},
                offline: {}
            }
        },

        updateApi: function (action) {
            const self = this;
            loading();
            const url = BASE_URL + '/admin/settings/update-api/' + self.prefix + '/' + action;
            let data = self[action];
            const headers = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            };
            axios.post(url, data, headers)
                .then((response) => {
                    self.fetchAll();
                    self.clearErrors();
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        self.errors[action] = error.response.data.errors
                    }
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        }
    }
}
</script>
