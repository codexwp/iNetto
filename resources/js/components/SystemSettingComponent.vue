<template>
    <div class="row">
        <div class="col-md-3">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">System Setting</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="pill" href="#general-content" role="tab" aria-controls="general-content" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="smtp-tab" data-bs-toggle="pill" href="#smtp-content" role="tab" aria-controls="smtp-content" aria-selected="true">SMTP</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="auto-sms-tab" data-bs-toggle="pill" href="#auto-sms-content" role="tab" aria-controls="auto-sms-content" aria-selected="true">Auto SMS</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cron-tab" data-bs-toggle="pill" href="#cron-content" role="tab" aria-controls="smtp-content" aria-selected="true">Cron Job</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-md-9">
            <div class="tab-content p-0" id="v-pills-tabContent">

                <!--General-->
                <div class="tab-pane fade show active" id="general-content" role="tabpanel" aria-labelledby="general-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">General</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="title">Website Title</label>
                                        <div class="col-sm-9">
                                            <input v-model="general.title" :class="errors.general.title ? 'is-invalid' : ''" type="text" class="form-control" id="title" placeholder="Website title">
                                            <div v-if="errors.general.title" class="form-text text-danger"> {{ errors.general.title[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="logo_text">Logo Text</label>
                                        <div class="col-sm-9">
                                            <input v-model="general.logo_text" :class="errors.general.logo_text ? 'is-invalid' : ''" type="text" class="form-control" id="logo_text" placeholder="Logo text">
                                            <div v-if="errors.general.logo_text" class="form-text text-danger"> {{ errors.general.logo_text[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="logo">Logo Image</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" id="logo" @change="changeLogo">
                                            <div v-if="errors.general.logo" class="form-text text-danger"> {{ errors.general.logo[0] }} </div>
                                            <img v-if="general.logo_path" :src="`/storage/${general.logo_path}`" style="height: 50px;margin-top: 5px;"/>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="country">Country</label>
                                        <div class="col-sm-9">
                                            <select @change="changeCountry()" v-model="general.country_iso" :class="errors.general.country_iso ? 'is-invalid' : ''" id="country" class="form-select" required>
                                                <option v-for="country in countries" :value="country.iso">{{ country.country }}</option>
                                            </select>
                                            <div v-if="errors.general.country_iso" class="form-text text-danger"> {{ errors.general.country_iso[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="timezone">TimeZone</label>
                                        <div class="col-sm-9">
                                            <select v-model="general.time_zone" :class="errors.general.time_zone ? 'is-invalid' : ''" id="timezone" class="form-select" required>
                                                <option v-for="(tz,key) in time_zones" :value="key">{{ tz }}</option>
                                            </select>
                                            <div v-if="errors.general.time_zone" class="form-text text-danger"> {{ errors.general.time_zone[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="title">Grace Period</label>
                                        <div class="col-sm-9">
                                            <input v-model="general.grace_period" :class="errors.general.grace_period ? 'is-invalid' : ''" type="number" class="form-control" id="grace_period" placeholder="Days">
                                            <div v-if="errors.general.grace_period" class="form-text text-danger"> {{ errors.general.grace_period[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="contact_no">Contact No</label>
                                        <div class="col-sm-9">
                                            <input v-model="general.contact_no" :class="errors.general.contact_no ? 'is-invalid' : ''" type="text" class="form-control" id="contact_no" placeholder="Contact No"/>
                                            <div v-if="errors.general.contact_no" class="form-text text-danger"> {{ errors.general.contact_no[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="contact_email">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input v-model="general.contact_email" :class="errors.general.contact_email ? 'is-invalid' : ''" type="email" class="form-control" id="contact_email" placeholder="Contact email"/>
                                            <div v-if="errors.general.contact_email" class="form-text text-danger"> {{ errors.general.contact_email[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="location">Office Location</label>
                                        <div class="col-sm-9">
                                            <textarea v-model="general.location" :class="errors.general.location ? 'is-invalid' : ''" type="text" class="form-control" id="location" placeholder="Location address"></textarea>
                                            <div v-if="errors.general.location" class="form-text text-danger"> {{ errors.general.location[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="copyright">Copyright Info</label>
                                        <div class="col-sm-9">
                                            <textarea v-model="general.copyright" :class="errors.general.copyright ? 'is-invalid' : ''" type="text" class="form-control" id="copyright" placeholder="Copyright text"></textarea>
                                            <div v-if="errors.general.copyright" class="form-text text-danger"> {{ errors.general.copyright[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label required" for="force_https">Force HTTPS</label>
                                        <div class="col-sm-9 pt-2">
                                            <input v-model="general.force_https" class="form-check-input" type="checkbox" value="1" id="force_https">
                                            <small class="ms-3">It is recommended to check the website HTTPS first. <a :href="httpsURL()" target="_blank">{{ httpsURL() }}</a></small>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('general')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--SMTP-->
                <div class="tab-pane fade" id="smtp-content" role="tabpanel" aria-labelledby="smtp-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">SMTP</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="host">Host</label>
                                        <div class="col-sm-9">
                                            <input v-model="smtp.host" :class="errors.smtp.host ? 'is-invalid' : ''" type="text" class="form-control" id="host" placeholder="Host IP/domain">
                                            <div v-if="errors.smtp.host" class="form-text text-danger"> {{ errors.smtp.host[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="host">Port</label>
                                        <div class="col-sm-9">
                                            <input v-model="smtp.port" :class="errors.smtp.port ? 'is-invalid' : ''" type="number" class="form-control" id="port" placeholder="Port (Ex. 25, 465)">
                                            <div v-if="errors.smtp.port" class="form-text text-danger"> {{ errors.smtp.port[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="host">Username</label>
                                        <div class="col-sm-9">
                                            <input v-model="smtp.username" :class="errors.smtp.username ? 'is-invalid' : ''" type="text" class="form-control" id="username" placeholder="Username">
                                            <div v-if="errors.smtp.username" class="form-text text-danger"> {{ errors.smtp.username[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="password">Password</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input v-model="smtp.password" :class="errors.smtp.password ? 'is-invalid' : ''" type="password" class="form-control toggle-password-input" id="password" placeholder="Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                            <div v-if="errors.smtp.password" class="form-text text-danger"> {{ errors.smtp.password[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="encryption">Encryption</label>
                                        <div class="col-sm-9">
                                            <select v-model="smtp.encryption" :class="errors.smtp.encryption ? 'is-invalid' : ''" id="encryption" class="form-select" required>
                                                <option :value="null">None</option>
                                                <option value="ssl">SSL</option>
                                                <option value="tls">TLS</option>
                                            </select>
                                            <div v-if="errors.smtp.encryption" class="form-text text-danger"> {{ errors.smtp.encryption[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="from_address">From Address</label>
                                        <div class="col-sm-9">
                                            <input v-model="smtp.from_address" :class="errors.smtp.from_address ? 'is-invalid' : ''" type="text" class="form-control" id="from_address" placeholder="From email">
                                            <div v-if="errors.smtp.from_address" class="form-text text-danger"> {{ errors.smtp.from_address[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="from_name">From Name</label>
                                        <div class="col-sm-9">
                                            <input v-model="smtp.from_name" :class="errors.smtp.from_name ? 'is-invalid' : ''" type="text" class="form-control" id="from_name" placeholder="From name">
                                            <div v-if="errors.smtp.from_name" class="form-text text-danger"> {{ errors.smtp.from_name[0] }} </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('smtp')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Auto SMS-->
                <div class="tab-pane fade" id="auto-sms-content" role="tabpanel" aria-labelledby="auto-sms-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Auto SMS</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label" >Shortcodes</label>
                                    <div class="col-sm-9">
                                        <button code="{user_d}" class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">user_d</button>
                                        <button code="{name}" class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">name</button>
                                        <button code="{username}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">username</button>
                                        <button code="{amount}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">amount</button>
                                        <button code="{package}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">package</button>
                                        <button code="{package_price}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">package_price</button>
                                        <button code="{expire_at}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">expire_at</button>
                                        <button code="{currency}"  class="btn btn-sm btn-outline-secondary ms-3 mb-3 btnShortcode">currency</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="before_expire_active">Remainder Active</label>
                                    <div class="col-sm-9">
                                        <input v-model="autosms.before_expire_active" class="form-check-input mt-2" type="checkbox" value="1" id="before_expire_active">
                                        <div v-if="errors.autosms.before_expire_active" class="form-text text-danger"> {{ errors.autosms.before_expire_active[0] }} </div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="before_expire_days">Before Days</label>
                                    <div class="col-sm-9">
                                        <input v-model="autosms.before_expire_days" class="form-control" type="number" id="before_expire_days" placeholder="number of days before expire"/>
                                        <div v-if="errors.autosms.before_expire_days" class="form-text text-danger"> {{ errors.autosms.before_expire_days[0] }} </div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="before_expire_message">Remainder Message</label>
                                    <div class="col-sm-9">
                                        <textarea v-model="autosms.before_expire_message" class="form-control" type="text" id="before_expire_message"></textarea>
                                        <div v-if="errors.autosms.before_expire_message" class="form-text text-danger"> {{ errors.autosms.before_expire_message[0] }} </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="after_expired_active">Expired Active</label>
                                    <div class="col-sm-9">
                                        <input v-model="autosms.after_expired_active" class="form-check-input mt-2" type="checkbox" value="1" id="after_expired_active">
                                        <div v-if="errors.autosms.after_expired_active" class="form-text text-danger"> {{ errors.autosms.after_expired_active[0] }} </div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="after_expired_message">Expired Message</label>
                                    <div class="col-sm-9">
                                        <textarea v-model="autosms.after_expired_message" class="form-control" type="text" id="after_expired_message"></textarea>
                                        <div v-if="errors.autosms.after_expired_message" class="form-text text-danger"> {{ errors.autosms.after_expired_message[0] }} </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="after_billpay_active">BillPay Active</label>
                                    <div class="col-sm-9">
                                        <input v-model="autosms.after_billpay_active" class="form-check-input mt-2" type="checkbox" value="1" id="after_billpay_active">
                                        <div v-if="errors.autosms.after_billpay_active" class="form-text text-danger"> {{ errors.autosms.after_billpay_active[0] }} </div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-3 col-form-label required" for="after_billpay_message">BillPay Message</label>
                                    <div class="col-sm-9">
                                        <textarea v-model="autosms.after_billpay_message" class="form-control" type="text" id="after_billpay_message"></textarea>
                                        <div v-if="errors.autosms.after_billpay_message" class="form-text text-danger"> {{ errors.autosms.after_billpay_message[0] }} </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button @click="updateApi('autosms')" type="button" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!--Cron Jobs-->
                <div class="tab-pane fade" id="cron-content" role="tabpanel" aria-labelledby="cron-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Cron Job</h5>
                            <button @click="executeCron()" class="btn btn-sm float-end btn-outline-secondary">Execute</button>

                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-12 mb-6">

                                    <p style="line-height: 1.5rem;" class="mb-0">
                                        <span >Set the following command as CRON service in your hosting server. <br>It should be run in every 15 minutes (*/15 * * * *)</span> <br><br>
                                        <small>
                                            <strong>Command : </strong> <br><code> /usr/bin/php /home/{username}/public_html/artisan task:daily >/dev/null 2>&1</code><br>
                                        </small>
                                    </p>

                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="table-responsive text-nowrap table-fixed-header">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Auto</th>
                                                <th v-if="0" class="text-center">Status</th>
                                                <th class="text-center">Note</th>

                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0 table-body">
                                            <tr v-for="cron in cron_jobs">
                                                <td class="text-center">{{ cron.id }}</td>
                                                <td class="text-center">{{ modifyDate(cron.created_at) }}</td>
                                                <td class="text-center">{{ cron.is_automatic }}</td>
                                                <td v-if="0" class="text-center" :class="cron.status ? 'text-success' : 'text-danger'">{{ cron.status ? 'Success' : 'Failed' }}</td>
                                                <td class="text-center">{{ cron.note }}</td>
                                            </tr>
                                            <tr v-if="cron_jobs.length == 0">
                                                <td class="text-center" colspan="5">No records</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
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


import moment from "moment";

export default {
    name: "SystemSettingComponent",
    props: {
        countries: {
            type: Array,
            required: true,
        },
        time_zones: {
            type: Object,
            required: true,
        },
    },
    data: () => {
        return {
            prefix : 'system',
            general : {
                currency_code : '',
                currency_symbol : '',
            },
            smtp : {},
            autosms : {},
            cron_jobs : [],
            errors : {
                general : {},
                smtp : {},
                autosms : {},
            }
        }
    },
    mounted: function () {
        this.fetchAll();
        $('.btnShortcode').click(function() {
            var codeValue = $(this).attr('code');
            navigator.clipboard.writeText(codeValue).then(function() { console.log('copied') });
        });
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
                    self.changeCountry();
                })
                .catch(error => {
                    console.log(error);
                    notify('Data fetch error', 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },

        clearErrors: function () {
            this.errors = {
                general : {},
                smtp : {},
                autosms: {}
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
        changeLogo: function (event) {
            this.general.logo = event.target.files[0];
        },
        executeCron: function () {
            const self = this;
            loading();
            const url = BASE_URL + '/common/execute-cron';
            axios.post(url)
                .then((response) => {
                    self.fetchAll();
                    notify(response.data.message, 'success');
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

        changeCountry: function () {
            let iso = this.general.country_iso;
            let index = this.countries.findIndex(item => item.iso === iso);
            if(index > -1) {
                this.general.currency_code = this.countries[index].currency_code;
                this.general.currency_symbol = this.countries[index].currency_symbol;
            }
        },

        httpsURL: function () {
            let base_url = BASE_URL;
            base_url = base_url.replace('http', 'https');
            return base_url;
        }

    }
}
</script>
