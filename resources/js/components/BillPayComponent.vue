<template>
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Bill Pay <span v-if="newPackage">( Change Package )</span></h5>
                    <div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody class="table-border-bottom-0 table-body">
                            <tr v-if="newPackage">
                                <td width="50%" class="fw-bold text-end">Package</td><td><span class="text-strike-through text-danger">{{ myPackage.name }}</span> <span>{{ newPackage.name }}</span></td>
                            </tr>
                            <tr v-else>
                                <td width="50%" class="fw-bold text-end">Package</td><td>{{ myPackage.name }}</td>
                            </tr>
                            <tr>
                                <td width="50%" class="fw-bold text-end">Validity</td><td>{{ myPackage.validity +' '+myPackage.validity_unit }}</td>
                            </tr>
                            <tr>
                                <td width="50%" class="fw-bold text-end">Duration</td><td>{{ duration.start_at }} <span class="text-dark fs-5">～</span> {{ duration.expire_at }}</td>
                            </tr>
                            <tr>
                                <td width="50%" class="fw-bold text-end">Total Payable</td><td><h4 class="mb-0">{{ currency +' '+ getPrice() }}</h4></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="gateways row mt-6 text-center">
                        <div class="col-sm-12">
                            <p class="fw-bold text-warning">Select Payment Method</p><hr>
                        </div>
                        <div v-for="(gw, i) in gateways" class='col-sm-12 my-2'>
                            <input v-model="selected_gateway" type="radio" name="imgbackground" :id="'gw_'+i" class="d-none imgbgchk" :value="gw.name">
                            <label :for="'gw_'+i">
                                <img :src="gw.logo">
                                <div class="tick_container">
                                    <div class="tick"><i class='bx bx-check'></i></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <hr class="mb-0">
                </div>

                <div v-if="!(selected_gateway !== '' && selected_gateway === 'offline')" class="card-footer text-center">
                    <button @click="startPayment" class="btn btn-outline-primary" :disabled="selected_gateway === ''">Start Payment</button>
                </div>
                <div v-else-if="offlineMessage" class="card-footer text-center">
                    <strong><span class="border-bottom">Offline Message</span></strong>
                    <p class="mt-2" v-html="offlineMessage"></p>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    name: "BillPayComponent",
    props: {
        user: {
            type: Object,
            required: true,
        },
        myPackage: {
            type: Object,
            required: true,
        },
        newPackage: {
            type: Object,
        },
        gateways: {
            type: Object,
        },
        duration: {
            type: Object,
            required: true,
        },
        offlineMessage: {
            type: String,
        },
        currency: {
            type : String,
            required : true
        }
    },

    data: () => {
        return {
            selected_gateway : '',
            package_id : ''
        }
    },
    mounted: function () {
        if(this.newPackage) {
            this.package_id = this.newPackage.id;
        } else {
            this.package_id = this.myPackage.id;
        }
    },

    methods: {
        getPrice: function () {
            if(this.newPackage) {
                return this.newPackage.price;
            } else {
                return this.myPackage.price;
            }
        },
        startPayment: function () {
            if(this.selected_gateway === 'offline') {
                return;
            }

            loading();
            let url = BASE_URL + '/payments/create/' + this.selected_gateway;
            const data = {
                gateway : this.selected_gateway,
                package_id : this.package_id
            }
            axios.post(url , data)
                .then((response) => {
                    location.href = response.data.data.payment_url;
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },

    }
}
</script>
