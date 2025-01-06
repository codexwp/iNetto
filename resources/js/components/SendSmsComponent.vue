<template>
    <div class="row">
        <div class="col-md-3">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">SMS</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="send-sms-tab" data-bs-toggle="pill" href="#send-sms-content" role="tab" aria-controls="send-sms-content" aria-selected="true">Send SMS</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="templates-tab" data-bs-toggle="pill" href="#templates-content" role="tab" aria-controls="templates-content" aria-selected="true">Templates</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="tab-content p-0" id="v-pills-tabContent">

                <!--Send-->
                <div class="tab-pane fade show active" id="send-sms-content" role="tabpanel" aria-labelledby="send-sms-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Send SMS</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-10">


                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-9">
                                            <h5 class="">Balance : <span class="text-primary"> {{ balance }}</span> <a @click="checkSmsBalance" href="#" class="fs-6 ms-3 text-warning">check now</a> </h5>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="method_radio">Method</label>
                                        <div class="col-sm-9 pt-2" id="method_radio">
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.method" class="form-check-input" type="radio" id="manual" value="manual">
                                                <label class="form-check-label" for="manual">Manual</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.method" class="form-check-input" type="radio" id="dynamic" value="dynamic">
                                                <label class="form-check-label" for="dynamic">Dynamic</label>
                                            </div>
                                            <div v-if="errors.sms.method" class="form-text text-danger"> {{ errors.sms.method[0] }} </div>
                                        </div>
                                    </div>

                                    <div v-if="sms.method=='dynamic'" class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="type_area">Receiver</label>
                                        <div class="col-sm-9 pt-2" id="type_area">
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.receiver" class="form-check-input" type="radio" id="user" value="user">
                                                <label class="form-check-label" for="user">User</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.receiver" class="form-check-input" type="radio" id="seller" value="seller">
                                                <label class="form-check-label" for="seller">Seller</label>
                                            </div>
                                            <div v-if="errors.sms.receiver" class="form-text text-danger"> {{ errors.sms.receiver[0] }} </div>
                                        </div>
                                    </div>


                                    <div v-if="sms.method=='dynamic' && sms.receiver=='user'" class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="seller_id">Filter <small>(by seller)</small></label>
                                        <div class="col-sm-9">
                                            <select v-model="sms.seller_id" :class="errors.sms.seller_id ? 'is-invalid' : ''" id="seller_id" class="form-select">
                                                <option value="">All Seller</option>
                                                <option v-for="seller in sellers" :value="seller.id">{{ seller.name }}</option>
                                            </select>
                                            <div v-if="errors.sms.seller_id" class="form-text text-danger"> {{ errors.sms.seller_id[0] }} </div>
                                        </div>
                                    </div>

                                    <div v-if="sms.method=='manual'" class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="mobile">Mobile No</label>
                                        <div class="col-sm-9">
                                            <input v-model="sms.mobile" :class="errors.sms.mobile ? 'is-invalid' : ''" type="text" class="form-control" id="mobile" placeholder="Mobile number (ex. 0171xxxx,081xxxxx)">
                                            <div v-if="errors.sms.mobile" class="form-text text-danger"> {{ errors.sms.mobile[0] }} </div>
                                        </div>
                                    </div>

                                    <div v-if="sms.method=='dynamic' && sms.receiver == 'user'" class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="condition_area">Condition</label>
                                        <div class="col-sm-9 pt-2" id="condition_area">
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.condition" class="form-check-input" type="radio" id="no_condition" value="none">
                                                <label class="form-check-label" for="no_condition">None</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.condition" class="form-check-input" type="radio" id="enabled" value="enabled">
                                                <label class="form-check-label" for="enabled">Enabled</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.condition" class="form-check-input" type="radio" id="disabled" value="disabled">
                                                <label class="form-check-label" for="disabled">Disabled</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.condition" class="form-check-input" type="radio" id="expired" value="expired">
                                                <label class="form-check-label" for="expired">Expired</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input v-model="sms.condition" class="form-check-input" type="radio" id="not_expired" value="not_expired">
                                                <label class="form-check-label" for="not_expired">Not Expired</label>
                                            </div>
                                            <div v-if="errors.sms.condition" class="form-text text-danger"> {{ errors.sms.condition[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="message">Message</label>
                                        <div class="col-sm-9">
                                            <textarea v-model="sms.message" class="form-control" type="text" id="message" style="min-height: 150px;"></textarea>
                                            <div v-if="errors.sms.message" class="form-text text-danger"> {{ errors.sms.message[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="template">Template</label>
                                        <div class="col-sm-9">
                                            <select v-model="sms.template" @change="selectSmsTemplate" id="template" class="form-select">
                                                <option value="">Select One</option>
                                                <option v-for="(template,index) in templates" :value="index">{{ template.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="sendSms" type="button" class="btn btn-primary">Send SMS</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Templates-->
                <div class="tab-pane fade" id="templates-content" role="tabpanel" aria-labelledby="templates-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Templates</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">

                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered">
                                            <tbody class="table-border-bottom-0 table-body">
                                                <tr v-for="(template, index) in templates">
                                                    <td>{{ index+1 }}</td>
                                                    <td>
                                                        <input v-model="template.name" :class="errors.templates && errors.templates[index+'.name'] ? 'is-invalid' : ''" type="text" class="form-control" placeholder="Template name"/>
                                                    </td>
                                                    <td>
                                                        <textarea v-model="template.message" :class="errors.templates && errors.templates[index+'.message'] ? 'is-invalid' : ''" class="form-control" placeholder="Write message"></textarea>
                                                    </td>
                                                    <td>
                                                        <a @click="deleteTemplate(index)" href="javascript:void(0)" class="btn-remove"><i class="bx bx-trash text-danger"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="addTemplate" type="button" class="btn btn-outline-secondary">Add New</button>
                                    <button @click="updateApi('templates')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
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
    name: "SendSmsComponent",
    props: {},
    data: () => {
        return {
            prefix : 'send_sms',
            templates : [{
                'name' : '',
                'message' : '',
            }],
            configs: {},
            sms:{
                method:'manual',
                receiver : 'user',
                seller_id : '',
                user_id : '',
                message : '',
                template: '',
                condition: 'none'
            },
            balance:'NA',
            sellers:[],
            users:[],
            errors : {
                configs : {},
                templates : [],
                sms:{}
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
                templates : {},
                configs : {},
                sms:{}
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
        },
        addTemplate: function () {
          this.templates.push({name:'',message:''});
        },
        deleteTemplate: function (index) {
            this.templates.splice(index, 1);
        },
        selectSmsTemplate: function () {
            this.sms.message = this.templates[this.sms.template].message;
        },
        sendSms: function () {
            const self = this;
            loading();
            const url = BASE_URL + '/admin/sms/send';
            axios.post(url, this.sms)
                .then((response) => {
                    this.sms = {
                        method:'manual',
                        type : 'user',
                        seller_type : 'all',
                        seller_id : '',
                        user_type : 'all',
                        user_id : '',
                        message : '',
                        template: '',
                        condition: 'none'
                    };
                    notify(response.data.message, 'success');
                    self.clearErrors();
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        self.errors.sms = error.response.data.errors
                    }
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        checkSmsBalance: function () {
            self = this;
            const url = BASE_URL + '/admin/sms/balance';
            loading();
            axios.get(url)
                .then((response) => {
                    let data = response.data;
                    self.balance = data.data.balance;
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
