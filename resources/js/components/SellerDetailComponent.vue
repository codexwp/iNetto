<template>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Seller Details</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="overview-tab" data-bs-toggle="pill" href="#overview-content" role="tab" aria-controls="overview-content" aria-selected="true">Overview</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="payments-tab" data-bs-toggle="pill" href="#payments-content" role="tab" aria-controls="payments-tab" aria-selected="false">Payments</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="personal-info-tab" data-bs-toggle="pill" href="#personal-info-content" role="tab" aria-controls="personal-info-tab" aria-selected="false">Personal Info</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="transfer-user-tab" data-bs-toggle="pill" href="#transfer-user-content" role="tab" aria-controls="transfer-user-tab" aria-selected="false">Transfer Users</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="change-user-pass-tab" data-bs-toggle="pill" href="#change-user-pass-content" role="tab" aria-controls="change-user-pass-content" aria-selected="false">Change Password</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="others-tab" data-bs-toggle="pill" href="#others-content" role="tab" aria-controls="others-content" aria-selected="false">Others</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content p-0" id="v-pills-tabContent">

                <!--Overview-->
                <div class="tab-pane fade show active" id="overview-content" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between mb-1">
                            <h5 class="mb-0">{{ seller.name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-3">
                                            <p class="mb-1 title">ID</p>
                                            <p class="card-title text-primary mb-0 text-end"> {{ seller.id }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-3">
                                            <p class="mb-1 title">Balance</p>
                                            <p class="card-title text-primary mb-0 text-end"> {{ currency }} {{ seller.balance }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-3">
                                            <p class="mb-1 title">Total Users</p>
                                            <p class="card-title mb-0 text-end"> {{ seller.user_count }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-3">
                                            <p class="mb-1 title">Tariff</p>
                                            <p class="card-title mb-0 text-end"> {{ seller.tariff?.name }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!--Payments-->
                <div class="tab-pane fade" id="payments-content" role="tabpanel" aria-labelledby="payments-tab">
                    <div class="card ">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Payments<small><a :href="seeAllPaymentsLink()" class="ms-3">See All</a></small></h5>
                            <button @click="isFundTransferShowing = !isFundTransferShowing;transfer.type='deposit';" :class="isFundTransferShowing ? 'btn-outline-secondary' : 'btn-outline-primary'"  class="btn btn-sm float-end">{{ isFundTransferShowing ? 'Close' : 'Fund Transfer'}}</button>
                        </div>

                        <div class="card-body">

                            <div v-show="isFundTransferShowing" class="row justify-content-center mb-6">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row mb-6">
                                                <label class="col-sm-4 col-form-label required" for="type">Type</label>
                                                <div class="col-sm-8">
                                                    <select v-model="transfer.type" id="type" class="form-select">
                                                        <option v-for="t in types" :value="t">{{ t }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-6">
                                                <label class="col-sm-4 col-form-label required" for="amount">Amount ({{currency}})</label>
                                                <div class="col-sm-8">
                                                    <input v-model="transfer.amount" type="number" class="form-control" id="amount" placeholder="Amount">
                                                </div>
                                            </div>

                                            <div class="row mb-6">
                                                <label class="col-sm-4 col-form-label" for="note">Reference</label>
                                                <div class="col-sm-8">
                                                    <textarea v-model="transfer.note" type="text" class="form-control" id="note" placeholder="Note (optional)"></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-8 offset-sm-4">
                                                    <button @click="fundTransferConfirm" type="button" class="btn btn-primary btn-save ms-5" :disabled="!(transfer.type && transfer.amount)">Transfer Now</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive text-nowrap table-fixed-header">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0 table-body">
                                    <tr v-if="payments.length" v-for="payment in payments">
                                        <td>{{ payment.id }}</td>
                                        <td>{{ modifyDate(payment.created_at) }}</td>
                                        <td :class="getPaymentTypeClass(payment.type)">{{ payment.type }}</td>
                                        <td>{{ currency }} {{ payment.amount }}</td>
                                        <td><span v-if="payment.cost">{{ currency }} {{ payment.cost }}</span></td>
                                        <td><span class="badge bg-label-success">{{ payment.status }}</span></td>
                                    </tr>
                                    <tr v-else>
                                        <td class="text-center" colspan="5">No records</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <!--Personal Info-->
                <div class="tab-pane fade" id="personal-info-content" role="tabpanel" aria-labelledby="personal-info-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Personal Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="name">Name</label>
                                        <div class="col-sm-9">
                                            <input v-model="seller.name" :class="seller_errors.name ? 'is-invalid' : ''" type="text" class="form-control" id="name" placeholder="Seller name">
                                            <div v-if="seller_errors.name" class="form-text text-danger"> {{ seller_errors.name[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="email">Email</label>
                                        <div class="col-sm-9">
                                            <input v-model="seller.email" :class="seller_errors.email ? 'is-invalid' : ''" type="email" class="form-control" id="email" placeholder="Email address">
                                            <div v-if="seller_errors.email" class="form-text text-danger"> {{ seller_errors.email[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="mobile" >Mobile</label>
                                        <div class="col-sm-9">
                                            <input v-model="seller.mobile"  type="tel" :class="seller_errors.email ? 'is-invalid' : ''"  class="form-control " id="mobile" placeholder="Enter number (without country code)">
                                            <div v-if="seller_errors.mobile" class="form-text text-danger"> {{ seller_errors.mobile[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="tariff_id">Tariff</label>
                                        <div class="col-sm-9">
                                            <select v-model="seller.tariff_id" :class="seller_errors.tariff_id ? 'is-invalid' : ''" id="tariff_id" class="form-select">
                                                <option v-for="t in tariffs" :value="t.id">{{ t.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active">Login Status</label>
                                        <div class="col-sm-9">
                                            <select v-model="seller.is_active" :class="seller_errors.is_active ? 'is-invalid' : ''" id="is_active" class="form-select">
                                                <option :value="1">Active</option>
                                                <option :value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active_user_sms">User SMS</label>
                                        <div class="col-sm-9">
                                            <select v-model="seller.is_active_user_sms" :class="seller_errors.is_active_user_sms ? 'is-invalid' : ''" id="is_active_user_sms" class="form-select">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <div class="form-text">If inactive, seller users won't receive SMS</div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="govt_id">Govt.ID</label>
                                        <div class="col-sm-9">
                                            <input v-model="seller.govt_id" :class="seller_errors.govt_id ? 'is-invalid' : ''" type="text" class="form-control" id="govt_id" placeholder="NID/Driver License/Passport No">
                                            <div v-if="seller_errors.govt_id" class="form-text text-danger"> {{ seller_errors.govt_id[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-sm-3 col-form-label" for="zip_code">Address</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="seller.zip_code" :class="seller_errors.zip_code ? 'is-invalid' : ''" type="text" value="" class="form-control" id="zip_code" placeholder="Zip code">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="seller.state" :class="seller_errors.state ? 'is-invalid' : ''" type="text" value="" class="form-control" id="state" placeholder="State">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="seller.city" :class="seller_errors.city ? 'is-invalid' : ''" type="text" value="" class="form-control " id="city" placeholder="City">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="seller.town" :class="seller_errors.town ? 'is-invalid' : ''" type="text" value="" class="form-control" id="town" placeholder="Town/Area">
                                                </div>
                                                <div class="col-sm-12 mb-6">
                                                    <input v-model="seller.street" :class="seller_errors.street ? 'is-invalid' : ''" type="text" value="" class="form-control" id="street" placeholder="Street/House">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <button @click="updateApi('personal-info')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!--Transfer Users-->
                <div class="tab-pane fade" id="transfer-user-content" role="tabpanel" aria-labelledby="transfer-user-tab">
                    <div class="card">

                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Transfer {{ seller.user_count }} Users</h5>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <div class="row mb-6">
                                        <label class="col-sm-4 col-form-label required" for="transfer_seller_id">New Seller</label>
                                        <div class="col-sm-8">
                                            <select v-model="new_seller_id" id="transfer_seller_id" class="form-select">
                                                <option value="">Select One</option>
                                                <option v-for="s in sellers" :value="s.id">{{ s.id + ': ' +s.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button :disabled="!(new_seller_id && seller.user_count)" @click="userTransferConfirm()" type="button" class="btn btn-primary btn-save ms-5">Transfer</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!--Change User/Password-->
                <div class="tab-pane fade" id="change-user-pass-content" role="tabpanel" aria-labelledby="change-user-pass-tab">
                    <div class="card">

                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Change Password</h5>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="password">New Password</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input v-model="seller.password" :class="seller_errors.password ? 'is-invalid' : ''" type="password" class="form-control toggle-password-input" id="password" placeholder="New Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                            <div v-if="seller_errors.password" class="form-text text-danger"> {{ seller_errors.password[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="password_confirmation">Password<small> (confirm)</small></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input v-model="seller.password_confirmation" :class="seller_errors.password_confirmation ? 'is-invalid' : ''" type="password" class="form-control toggle-password-input" id="password_confirmation" placeholder="Confirm Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                            <div v-if="seller_errors.password_confirmation" class="form-text text-danger"> {{ seller_errors.password_confirmation[0] }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('seller-password')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!--Others-->
                <div class="tab-pane fade" id="others-content" role="tabpanel" aria-labelledby="others-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Others</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-5 col-sm-4 mx-auto">
                                <button @click="deleteConfirm()" class="btn btn-outline-danger" type="button">Delete</button>
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
    name: "SellerDetailComponent",
    props: {
        seller_id: {
            type: Number,
            required: true,
        },
        tariffs: {
            type: Array,
            required: true,
        },
        types: {
            type: Array,
            required: true
        },
        currency: {
            type : String,
            required : true
        }
    },

    data: () => {
        return {
            seller : {},
            seller_errors : {},
            sellers: [],
            transfer: {},
            isFundTransferShowing : false,
            payments : [],
            new_seller_id: '',
        }
    },
    mounted: function () {
        this.fetchSeller();
    },
    methods: {
        fetchSeller: function () {
            const self = this;
            loading();
            axios.get(BASE_URL + '/admin/sellers/' + self.seller_id + '/fetch')
                .then((response) => {
                    self.seller = response.data.data.seller;
                    self.payments = response.data.data.payments;
                    self.sellers = response.data.data.sellers
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        deleteConfirm: function () {
            confirmModal(this.delete, '', 'Are you sure to delete?', '');
        },
        delete: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/sellers/' + this.seller.id + '/delete';
            axios.post(url)
                .then((response) => {
                    notify(response.data.message, 'success');
                    location.href = BASE_URL + '/admin/sellers/';
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        modifyDate: function (date) {
          return moment(date).format('YYYY-MM-DD  h:mm a');
        },
        fundTransferConfirm: function () {
          confirmModal(this.fundTransfer, '', 'Are you sure to transfer?', '');
        },
        fundTransfer: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/payments/fund-transfer';
            let data = self.transfer;
            data.seller_id = self.seller.id;
            axios.post(url , data)
                .then((response) => {
                    self.isFundTransferShowing = false;
                    self.fetchSeller();
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        updateApi: function (action) {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/sellers/' + this.seller_id + '/update-api/';
            axios.post(url + action, self.seller)
                .then((response) => {
                    self.fetchSeller();
                    this.seller_errors = {};
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.seller_errors = error.response.data.errors;
                    }
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        getPaymentTypeClass: function (type) {
            let cls = {
                bill : 'text-danger',
                deposit : 'text-primary',
                withdraw : 'text-danger',
                return : 'text-danger'
            }
            return cls[type];
        },
        userTransferConfirm: function () {
            confirmModal(this.userTransfer, '', 'Are you sure to transfer?', '');
        },
        userTransfer: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/sellers/'+ self.seller.id +'/users/transfer';
            axios.post(url , {new_seller_id : self.new_seller_id})
                .then((response) => {
                    self.fetchSeller();
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        seeAllPaymentsLink: function () {
            return BASE_URL + '/admin/payments?seller_id='+this.seller_id;
        }
    }
}
</script>

<style scoped>

</style>
