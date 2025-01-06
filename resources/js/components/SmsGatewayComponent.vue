<template>
    <div class="row">
        <div class="col-md-3">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">SMS Gateways</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="default-tab" data-bs-toggle="pill" href="#default-content" role="tab" aria-controls="default-content" aria-selected="true">Set Default</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="bulksmsbd-tab" data-bs-toggle="pill" href="#bulksmsbd-content" role="tab" aria-controls="bulksmsbd-content" aria-selected="true">BulkSMSBD</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="twilio-tab" data-bs-toggle="pill" href="#twilio-content" role="tab" aria-controls="twilio-content" aria-selected="true">Twilio</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="tab-content p-0" id="v-pills-tabContent">

                <!--Set Default-->
                <div class="tab-pane fade show active" id="default-content" role="tabpanel" aria-labelledby="default-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Default</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">

                                    <div class="form-check mt-3">
                                        <input v-model="default_gateway.name" class="form-check-input" type="radio" value="bulksmsbd" id="bulksmsbd">
                                        <label class="form-check-label" for="bulksmsbd">
                                            BulkSMSBD
                                        </label>
                                    </div>

                                    <div class="form-check mt-3">
                                        <input v-model="default_gateway.name" class="form-check-input" type="radio" value="twilio" id="twilio">
                                        <label class="form-check-label" for="twilio">
                                            Twilio
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="row justify-content-center mt-10">
                                <div class="col-sm-8">

                                    <div class="form-check mt-3">
                                        <input v-model="default_gateway.is_active" class="form-check-input" type="checkbox" value="1" id="is_active_default_sms_gateway">
                                        <label class="form-check-label text-danger" for="is_active_default_sms_gateway">
                                            Enable sending SMS
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('default_gateway')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Bulk SMS BD-->
                <div class="tab-pane fade" id="bulksmsbd-content" role="tabpanel" aria-labelledby="bulksmsbd-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Bulk SMS BD</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <p><span class="text-danger">Bangladesh Only</span><br> To get credentials, visit here <a href="https://bulksmsbd.com/" target="_blank">https://bulksmsbd.com/</a> </p>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active">Active Status</label>
                                        <div class="col-sm-9">
                                            <input v-model="bulksmsbd.is_active" class="form-check-input mt-2" type="checkbox" value="1" id="is_active">
                                            <div v-if="errors.bulksmsbd.is_active" class="form-text text-danger"> {{ errors.bulksmsbd.is_active[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="api_key">API Key</label>
                                        <div class="col-sm-9">
                                            <input v-model="bulksmsbd.api_key" :class="errors.bulksmsbd.api_key ? 'is-invalid' : ''" type="text" class="form-control" id="api_key" placeholder="API key">
                                            <div v-if="errors.bulksmsbd.api_key" class="form-text text-danger"> {{ errors.bulksmsbd.api_key[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="sender_id">Sender ID</label>
                                        <div class="col-sm-9">
                                            <input v-model="bulksmsbd.sender_id" :class="errors.bulksmsbd.sender_id ? 'is-invalid' : ''" type="text" class="form-control" id="sender_id" placeholder="Sender id">
                                            <div v-if="errors.bulksmsbd.sender_id" class="form-text text-danger"> {{ errors.bulksmsbd.sender_id[0] }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('bulksmsbd')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--SMTP-->
                <!--Twilio-->
                <div class="tab-pane fade" id="twilio-content" role="tabpanel" aria-labelledby="twilio-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Twilio</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <p>To get credentials, visit here <a href="https://www.twilio.com/en-us/messaging/channels/sms" target="_blank">https://www.twilio.com/en-us/messaging/channels/sms</a> </p>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active_twilio">Active Status</label>
                                        <div class="col-sm-9">
                                            <input v-model="twilio.is_active" class="form-check-input mt-2" type="checkbox" value="1" id="is_active_twilio">
                                            <div v-if="errors.twilio.is_active" class="form-text text-danger"> {{ errors.twilio.is_active[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="twilio_sid">SID</label>
                                        <div class="col-sm-9">
                                            <input v-model="twilio.sid" :class="errors.twilio.sid ? 'is-invalid' : ''" type="text" class="form-control" id="twilio_sid" placeholder="SID">
                                            <div v-if="errors.twilio.sid" class="form-text text-danger"> {{ errors.twilio.sid[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="twilio_token">Token</label>
                                        <div class="col-sm-9">
                                            <input v-model="twilio.token" :class="errors.twilio.token ? 'is-invalid' : ''" type="text" class="form-control" id="twilio_token" placeholder="Token">
                                            <div v-if="errors.twilio.token" class="form-text text-danger"> {{ errors.twilio.token[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="twilio_from">From</label>
                                        <div class="col-sm-9">
                                            <input v-model="twilio.from" :class="errors.twilio.from ? 'is-invalid' : ''" type="text" class="form-control" id="twilio_from" placeholder="From">
                                            <div v-if="errors.twilio.from" class="form-text text-danger"> {{ errors.twilio.from[0] }} </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('twilio')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
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
    name: "SmsGatewayComponent",
    props: {},
    data: () => {
        return {
            prefix : 'sms_gateway',
            default_gateway: {
                name : 'bulksmsbd'
            },
            bulksmsbd : {},
            twilio : {},
            errors : {
                bulksmsbd : {},
                twilio : {}
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
                bulksmsbd : {},
                twilio : {}
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
