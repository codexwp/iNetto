<template>
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Bulk Payment Review</h5>
                    <div>
                    </div>
                </div>

                <div class="card-body">

                    <h3 v-if="isCompleted" class="text-primary text-center">Payment Completed</h3>
                    <div v-else class="table-responsive text-nowrap table-fixed-header" style="max-height: 600px;">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Package</th>
                                <th class="text-center">Start At</th>
                                <th class="text-center">Expire At</th>
                                <th class="text-center">Price({{ currency }})</th>
                                <th class="text-center">Cost({{ currency }})</th>

                            </tr>
                            </thead>
                            <tbody  v-for="(seller, seller_index) in sellers" class="table-border-bottom-0 table-body" :key="seller_index">
                                <tr v-for="(user, user_index) in seller.users" :key="seller_index + '_' +user_index">
                                    <td>{{ user.info.name }}</td>
                                    <td :class="user.info.is_active_client ? 'text-success' : 'text-danger'">{{ user.info.username }}</td>
                                    <td>
                                        <select @change="changePackage()" v-model="user.pid" class="form-control form-control-sm">
                                            <option :value="0">-----</option>
                                            <option v-for="pack in seller.packages" :value="pack.id">{{ pack.name }}</option>
                                        </select>
                                    </td>
                                    <td class="text-center">{{ user.duration.start_at }}</td>
                                    <td class="text-center">{{ user.duration.expire_at }}</td>
                                    <td class="text-end">{{ user.price }}</td>
                                    <td class="text-end">{{ user.cost }}</td>

                                </tr>
                                <tr style="background: #eee;">
                                    <td colspan="4" class="text-center">
                                        <strong>SELLER : </strong> {{ seller.info.name }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <strong>BALANCE : </strong>
                                        <span :class="getTotalCost(seller) > seller.info.balance ? 'text-danger': ''"> {{ currency }} {{ seller.info.balance}}</span>
                                    </td>
                                    <td class="text-end  fw-bold">TOTAL</td>
                                    <td class="text-end text-primary fw-bold">{{ currency }} {{ getTotalPrice(seller) }}</td>
                                    <td class="text-end text-primary fw-bold">{{ currency }} {{ getTotalCost(seller) }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer mt-3">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <a :href="backURL()" class="btn btn-outline-secondary">Back</a>
                            <button v-if="!isCompleted" @click="payNowConfirmation()" type="button" class="btn btn-primary btn-save ms-5">Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    name: "BulkPaymentComponent",
    props: {
        userPackages: {
            type: Array,
            required: true,
        },
        currency: {
            type : String,
            required : true
        }
    },

    data: () => {
        return {
            sellers : [],
            uidPids : [],
            isCompleted : false,
        }
    },
    mounted: function () {
        this.fetchData(this.userPackages);
    },

    methods: {
        backURL: function () {
          return BASE_URL+ '/admin/users';
        },
        fetchData: function (user_packages) {
            loading();
            let url = BASE_URL + '/admin/payments/bulk-payment-data';
            const data = {
                user_packages : JSON.stringify(user_packages),
            }
            axios.post(url , data)
                .then((response) => {
                    this.sellers = response.data.data;
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        changePackage: function () {
            let user_packages = [];
            for (let sid in this.sellers) {
                let seller = this.sellers[sid];
                for(let j = 0; j < seller.users.length; j++) {
                    let user = seller.users[j];
                    user_packages.push({
                        uid : user.info.id,
                        pid : user.pid,
                    })
                }
            }
            this.fetchData(user_packages);
        },
        getTotalPrice: function (seller) {
            let price = 0;
            for(let i = 0; i < seller.users.length; i++) {
                let package_price = seller.users[i].price;
                price += package_price;
            }
            return price;
        },
        getTotalCost: function (seller) {
            let cost = 0;
            for(let i = 0; i < seller.users.length; i++) {
                let package_cost = seller.users[i].cost;
                cost += package_cost;
            }
            return cost;
        },

        payNowConfirmation: function () {
            this.uidPids = [];
            for (let sid in this.sellers) {
                let seller = this.sellers[sid];
                let cost = this.getTotalCost(seller);
                if(seller.balance < cost) {
                    notify(`Seller '${seller.info.name}' do not have enough balance!!`, 'error')
                    return;
                }
                for(let j = 0; j < seller.users.length; j++) {
                    let user = seller.users[j];
                    if(user.pid) {
                        this.uidPids.push({
                            uid : user.info.id,
                            pid : user.pid,
                        })
                    }
                }
            }
            confirmModal(this.payNow, '', 'It may take few moments. Do not close the browser. Are you sure?', `${this.uidPids.length} Users Selected`, 'Yes, Pay Now')
        },
        payNow: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/payments/bulk-payment-process';
            const data = {
                user_packages : this.uidPids,
            }
            axios.post(url , data)
                .then((response) => {
                    self.isCompleted = true;
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });

        }

    }
}
</script>
