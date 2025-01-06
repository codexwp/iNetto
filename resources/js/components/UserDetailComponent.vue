<template>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">User Details</h5>
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
                            <a class="nav-link" id="change-status-expiry-tab" data-bs-toggle="pill" href="#change-status-expiry-content" role="tab" aria-controls="change-status-expiry-tab" aria-selected="false">Status & Expiry</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="change-user-pass-tab" data-bs-toggle="pill" href="#change-user-pass-content" role="tab" aria-controls="change-user-pass-content" aria-selected="false">User & Password</a>
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
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">{{ user_info.name }}</h5>
                            <button v-if="!isChartLive" @click="isChartLive = true; isChartLiveCount = 0; fetchInternetSpeedData()" class="btn btn-sm btn-outline-secondary float-end">Check</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="card overview-card">
                                        <div class="card-body p-3">
                                            <line-chart ref="chartRef" :data="chartData" :options="chartOptions"></line-chart>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">User</p>
                                            <p class="card-title mb-0 text-end"> {{ user_info.username }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">PPPoe</p>
                                            <p :class="user_info.is_active_client ? 'text-primary' : 'text-danger'" class="card-title mb-0 text-end"> {{ user_info.is_active_client ? 'Enabled' : 'Disabled' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">Expire</p>
                                            <p class="card-title text-primary mb-0 text-end"> {{ user_info.expire_at ?? 'NA' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">Package</p>
                                            <p class="card-title mb-0 text-end"> {{ user_info.package? user_info.package.name : 'NA' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">Bill</p>
                                            <p class="card-title mb-0 text-end"> {{ getPackagePrice()+currency }}  <small>/ {{ getPackageValidity() }}</small></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div class="card-body p-2">
                                            <p class="mb-1 title">Seller</p>
                                            <p class="card-title mb-0 text-end"> {{ user_info.seller?.name }} </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 mt-4">
                                    <div class="card overview-card">
                                        <div v-if="user_info.grace_at" class="card-body p-2">
                                            <p class="mb-1 title">Grace At</p>
                                            <p class="card-title mb-0 text-end"> {{ user_info.grace_at.split(' ')[0] }} </p>
                                        </div>
                                        <div v-else class="card-body p-2 text-center">
                                            <button @click="applyGraceConfirm()" class="btn btn-sm btn-outline-secondary my-2">Apply Grace</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!--Payments-->
                <div class="tab-pane fade" id="payments-content" role="tabpanel" aria-labelledby="payments-content-tab">

                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Payments <small><a :href="seeAllPaymentsLink()" class="ms-3">See All</a></small></h5>
                            <button @click="payBillForm()" :class="isPayBillShowing ? 'btn-outline-secondary' : 'btn-outline-primary'" class="btn btn-sm float-end"><i class='bx bx-dollar-circle me-1 lh-1' ></i>{{ isPayBillShowing ? 'Close' : 'Pay Bill'}}</button>
                        </div>

                        <div class="card-body">

                            <div v-show="isPayBillShowing" class="row justify-content-center mb-6">
                                <div class="col-sm-6">
                                    <div class="card" style="border-top: 2px solid #696cff6b;">
                                        <div class="card-header">
                                            <div style="color: darkcyan;"  class="mb-0 py-2 text-center d-inline-flex justify-content-center w-100"><i class='bx bx-user me-1'></i> <span>{{ user_info.username }}</span> <i class='bx bx-calendar ms-3 me-1' ></i> <span>{{ user_info.expire_at}}</span></div>
                                        </div>
                                        <div class="card-body">

                                            <div  v-if="bill.pay_by == 'seller' && 0" class="row mb-3">
                                                <label class="col-sm-4 col-form-label"></label>
                                                <div class="col-sm-8">
                                                    <div class="form-check d-inline-flex justify-content-center">
                                                        <input v-model="bill.is_deposit" class="form-check-input" type="checkbox" value="" id="depositBeforePay">
                                                        <label class="form-check-label ms-3 text-primary" for="depositBeforePay">
                                                            auto deposit cost & payment
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-6 mt-2">
                                                <label class="col-sm-4 col-form-label required" for="paid_by_radio">Paid By</label>
                                                <div class="col-sm-8 pt-2" id="paid_by_radio">
                                                    <div class="form-check form-check-inline">
                                                        <input @change="changeBillPayBy" v-model="bill.pay_by" class="form-check-input" type="radio" id="seller" value="seller">
                                                        <label class="form-check-label" for="seller">Seller ( <span :class="(getCost() > parseInt(user_info.seller?.balance)) ? 'text-danger' : ''">{{ user_info.seller?.balance + currency }}</span> )</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input @change="changeBillPayBy" v-model="bill.pay_by" class="form-check-input" type="radio" id="user" value="user">
                                                        <label class="form-check-label" for="user">User</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-6">
                                                <label class="col-sm-4 col-form-label required" for="package_id">Package</label>
                                                <div class="col-sm-8">
                                                    <select v-model="bill.package_id" :class="bill_errors.package_id ? 'is-invalid' : ''" id="package_id" class="form-select" @change="getDuration">
                                                        <option v-for="p in packages" :value="p.id">{{ p.name }}</option>
                                                    </select>
                                                    <div id="defaultFormControlHelp" class="form-text ps-2" style="color:#8f9193;">
                                                        Price : {{ getPackagePrice() + currency }} /{{ getPackageValidity() }} & Cost : {{ getCost() + currency }}
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mb-6">
                                                <label class="col-sm-4 col-form-label" for="note">Reference</label>
                                                <div class="col-sm-8">
                                                    <textarea v-model="bill.note" type="text" class="form-control" id="note" placeholder="Note (optional)"></textarea>
                                                </div>
                                            </div>

                                            <div v-if="duration.expire_at" class="row mb-6">
                                                <label class="col-sm-4 col-form-label" for="duration">Duration</label>
                                                <div class="col-sm-8">
                                                    <p id="duration" class="pt-2 text-dark mb-0">{{ duration.start_at }} <span class="text-dark fs-5">～</span> {{ duration.expire_at }}</p>
                                                    <p v-if="user_info.package_id != bill.package_id" class="text-danger fs-tiny">Billing cycle will be reset due to change of package </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-8 offset-sm-4">
                                                    <button @click="payBillConfirm" type="button" class="btn btn-primary btn-save ms-5" :disabled="getPackagePrice() == 'NA' || bill.pay_by == undefined || (bill.pay_by == 'seller' && getCost() > parseInt(user_info.seller?.balance))"><i class='bx bx-paper-plane me-1' ></i> Pay Now</button>
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
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Amount({{ currency}})</th>
                                        <th class="text-center">Cost({{ currency}})</th>
                                        <th class="text-center">Status</th>

                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0 table-body">
                                    <tr v-if="payments.length" v-for="payment in payments">
                                        <td class="text-center">{{ payment.id }}</td>
                                        <td class="text-center">{{ modifyDate(payment.created_at) }}</td>
                                        <td class="text-center">{{ payment.amount }}</td>
                                        <td class="text-center">{{ payment.cost ?? '' }}</td>
                                        <td class="text-center"><span class="badge bg-label-success">{{ payment.status }}</span></td>
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
                                            <input v-model="user_info.name" :class="personal_info_errors.name ? 'is-invalid' : ''" type="text" class="form-control" id="name" placeholder="Person/company name">
                                            <div v-if="personal_info_errors.name" class="form-text text-danger"> {{ personal_info_errors.name[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="email">Email</label>
                                        <div class="col-sm-9">
                                            <input v-model="user_info.email" :class="personal_info_errors.email ? 'is-invalid' : ''" type="email" class="form-control" id="email" placeholder="Email address">
                                            <div v-if="personal_info_errors.email" class="form-text text-danger"> {{ personal_info_errors.email[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="mobile">Mobile</label>
                                        <div class="col-sm-9">
                                            <input v-model="user_info.mobile" :class="personal_info_errors.mobile ? 'is-invalid' : ''" type="tel"  class="form-control " id="mobile" placeholder="Enter number (without country code)" >
                                            <div v-if="personal_info_errors.mobile" class="form-text text-danger"> {{ personal_info_errors.mobile[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="seller_id">Seller</label>
                                        <div class="col-sm-9">
                                            <select v-model="user_info.seller_id" id="seller_id" class="form-select">
                                                <option value="">Select One</option>
                                                <option v-for="s in sellers" :value="s.id">{{ s.id + ': ' +s.name }}</option>
                                            </select>
                                            <div v-if="personal_info_errors.seller_id" class="form-text text-danger"> {{ personal_info_errors.seller_id[0] }} </div>
                                        </div>
                                    </div>



                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="govt_id">Govt.ID</label>
                                        <div class="col-sm-9">
                                            <input v-model="user_info.govt_id" :class="personal_info_errors.govt_id ? 'is-invalid' : ''" type="text" class="form-control" id="govt_id" placeholder="NID/Driver License/Passport No">
                                            <div v-if="personal_info_errors.govt_id" class="form-text text-danger"> {{ personal_info_errors.govt_id[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-sm-3 col-form-label" for="zip_code">Address</label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="user_info.zip_code" :class="personal_info_errors.zip_code ? 'is-invalid' : ''" type="text" value="" class="form-control" id="zip_code" placeholder="Zip code">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="user_info.state" :class="personal_info_errors.state ? 'is-invalid' : ''" type="text" value="" class="form-control" id="state" placeholder="State">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="user_info.city" :class="personal_info_errors.city ? 'is-invalid' : ''" type="text" value="" class="form-control " id="city" placeholder="City">
                                                </div>
                                                <div class="col-sm-6 mb-6">
                                                    <input v-model="user_info.town" :class="personal_info_errors.town ? 'is-invalid' : ''" type="text" value="" class="form-control" id="town" placeholder="Town/Area">
                                                </div>
                                                <div class="col-sm-12 mb-6">
                                                    <input v-model="user_info.street" :class="personal_info_errors.street ? 'is-invalid' : ''" type="text" value="" class="form-control" id="street" placeholder="Street/House">
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

                <!--Change Status & Expiry-->
                <div class="tab-pane fade" id="change-status-expiry-content" role="tabpanel" aria-labelledby="change-status-expiry-tab">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Status & Expire Date</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-8">

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="expire_at">Expire Date</label>
                                        <div class="col-sm-9">
                                            <input v-model="user_info.expire_at" :class="personal_info_errors.expire_at ? 'is-invalid' : ''" type="date" class="form-control" id="expire_at" placeholder="Expire Date">
                                            <div v-if="personal_info_errors.expire_at" class="form-text text-danger"> {{ personal_info_errors.expire_at[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active">Login Status</label>
                                        <div class="col-sm-9">
                                            <select v-model="user_info.is_active" :class="personal_info_errors.is_active ? 'is-invalid' : ''" id="is_active" class="form-select" required>
                                                <option value="0">Disabled</option>
                                                <option value="1">Enabled</option>
                                            </select>
                                            <div v-if="personal_info_errors.is_active" class="form-text text-danger"> {{ personal_info_errors.is_active[0] }} </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="is_active_client">PPPoe Status</label>
                                        <div class="col-sm-9">
                                            <select v-model="user_info.is_active_client" :class="personal_info_errors.is_active_client ? 'is-invalid' : ''" id="is_active_client" class="form-select" required>
                                                <option value="0">Disabled</option>
                                                <option value="1">Enabled</option>
                                            </select>
                                            <div class="form-text">
                                                Mikrotik status is <span :class="server_pppoe_status?'text-success':'text-danger'">{{ server_pppoe_status == null ? 'unknown' : (server_pppoe_status ? 'enabled' : 'disabled') }} </span>
                                                <a @click="checkMikrotikStatus" href="javascript:void(0)"  class=" ms-5">check status</a>
                                            </div>
                                            <div v-if="personal_info_errors.is_active_client" class="form-text text-danger"> {{ personal_info_errors.is_active_client[0] }} </div>
                                        </div>
                                    </div>

                                 </div>

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <button @click="updateApi('status-expire')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
                                        </div>
                                    </div>
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
                                        <label class="col-sm-3 col-form-label" for="username">Username</label>
                                        <div class="col-sm-9">
                                            <input disabled v-model="user_info.username" type="text" class="form-control" id="username" placeholder="Username">
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label" for="current_password">Password</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input disabled v-model="user_info.key" type="password" class="form-control toggle-password-input" id="current_password" placeholder="Current Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="password">New Password</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input v-model="user_info.password" :class="personal_info_errors.password ? 'is-invalid' : ''" type="password" class="form-control toggle-password-input" id="password" placeholder="New Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                            <div v-if="personal_info_errors.password" class="form-text text-danger"> {{ personal_info_errors.password[0] }} </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-sm-3 col-form-label required" for="password_confirmation">Password<small> (confirm)</small></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <input v-model="user_info.password_confirmation" :class="personal_info_errors.password_confirmation ? 'is-invalid' : ''" type="password" class="form-control toggle-password-input" id="password_confirmation" placeholder="Confirm Password">
                                                <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                            </div>
                                            <div v-if="personal_info_errors.password_confirmation" class="form-text text-danger"> {{ personal_info_errors.password_confirmation[0] }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button :disabled="!(user_info.password && user_info.password_confirmation && (user_info.password == user_info.password_confirmation))" @click="updateApi('user-password')" type="button" class="btn btn-primary btn-save ms-5">Update</button>
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
                                <button @click="othersApiConfirm('synchronize')" class="btn btn-outline-primary" type="button">Synchronize</button>
                                <button @click="othersApiConfirm('delete')" class="btn btn-outline-danger" type="button">Delete</button>
                                <button @click="othersApiConfirm('delete-with-mikrotik')" class="btn btn-outline-danger" type="button">Delete with Mikrotik</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js';
import moment from "moment";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
);

export default {
    name: "UserDetailComponent",
    components: {
        LineChart: Line
    },
    props: {
        user_id: {
            type: Number,
            required: true,
        },
        currency: {
            type : String,
            required : true
        }
    },

    data: () => {
        return {
            bill : {},
            bill_errors : {},
            user_info : {},
            personal_info_errors : {},
            server_pppoe_status : null,
            packages: [],
            payments : [],
            sellers: [],
            isPayBillShowing: false,
            duration: {},
            isChartLive : false,
            isChartLiveCount : 0,
            chartData: {
                labels: ['na','na','na','na','na','na','na','na','na','na'],
                datasets: [
                    {
                        label: 'Download',
                        backgroundColor: '#42A5F5',
                        borderColor: '#42A5F5',
                        tension: 0.4,
                        data: [0,0,0,0,0,0,0,0,0,0]
                    },
                    {
                        label: 'Upload',
                        backgroundColor: '#FF5722',
                        borderColor: '#FF5722',
                        tension: 0.4,
                        data: [0,0,0,0,0,0,0,0,0,0]
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 0
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Mbps'
                        },
                        beginAtZero: true
                    }
                }
            }
        }
    },
    mounted: function () {
        this.fetchUser();
        //this.fetchInternetSpeedData();
    },

    methods: {
        modifyDate: function (date) {
            return moment(date).format('YYYY-MM-DD  h:mm a');
        },
        getDuration: function () {
            const self = this;
            let price = self.getPackagePrice();
            if(!self.isPayBillShowing || price == 'NA') { this.duration = {}; return; }

            loading();
            axios.get(BASE_URL + '/common/new-expire/' + self.user_info.id + '/' + self.bill.package_id)
                .then((response) => {
                    self.duration = response.data.data;
                })
                .catch(error => {
                })
                .finally(() => {
                    loading(false);
                });
        },
        payBillForm: function () {
            this.isPayBillShowing = !this.isPayBillShowing;
            this.bill.is_deposit = false;
            this.bill.pay_by = 'seller';
            this.getDuration();
        },
        payBillConfirm: function () {
          confirmModal(this.payBill, '', 'Are you sure to pay bill?', '');
        },
        changeBillPayBy: function () {
            this.bill.is_deposit = false;
        },
        payBill: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/payments/pay-bill';
            const data = {
                package_id : self.bill.package_id,
                user_id : self.user_info.id,
                is_deposit : self.bill.is_deposit,
                pay_by : self.bill.pay_by,
            }
            axios.post(url , data)
                .then((response) => {
                    self.isPayBillShowing = false;
                    self.fetchUser();
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        getPackagePrice: function () {
            this.bill.deposit_amount = this.getCost();
            const index = this.packages.findIndex(item => item.id === this.bill.package_id);
            return parseInt(index > -1 ? this.packages[index].price : -1)
        },
        getPackageValidity: function () {
            const index = this.packages.findIndex(item => item.id === this.bill.package_id);
            return index > -1 ? this.packages[index].valid : 'na';
        },
        getCost: function () {
            const index = this.packages.findIndex(item => item.id === this.bill.package_id);
            return parseInt(index > -1 ? this.packages[index].cost : -1)
        },
        fetchUser: function () {
            const self = this;
            loading();
            axios.get(BASE_URL + '/admin/users/' + self.user_id + '/fetch')
                .then((response) => {
                    self.user_info = response.data.data.user_info;
                    self.bill.package_id = self.user_info.package_id;
                    self.payments = response.data.data.payments;
                    self.packages = response.data.data.seller_packages;
                    self.sellers = response.data.data.sellers;
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
            let url = BASE_URL + '/admin/users/' + this.user_id + '/update-api/';
            axios.post(url + action, self.user_info)
                .then((response) => {
                    self.fetchUser();
                    this.personal_info_errors = {};
                    notify(response.data.message, 'success');
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.personal_info_errors = error.response.data.errors;
                    }
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        othersApiConfirm: function (action) {
          confirmModal(this.othersApi, action, 'Are you sure to do this operation?', '')
        },
        othersApi: function (action) {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/users/' + self.user_id + '/others-api/';
            axios.post(url + action)
                .then((response) => {
                    notify(response.data.message, 'success');
                    if(action == 'delete' || action == 'delete-with-mikrotik') {
                        location.href = BASE_URL + '/admin/users';
                    }
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },

        fetchInternetSpeedData: async function () {
            try {
                const url = BASE_URL + '/common/internet-speed/' + this.user_id + '/fetch';
                const response = await axios.get(url);
                const { downloadSpeed, uploadSpeed, timestamp } = response.data.data;
                //this.chartData.labels.push(timestamp);
                this.chartData.datasets[0].data = [...this.chartData.datasets[0].data, downloadSpeed];
                this.chartData.datasets[1].data = [...this.chartData.datasets[1].data, uploadSpeed];
                if (this.chartData.labels.length > 10) {
                    this.chartData.labels.shift();
                    this.chartData.datasets[0].data.shift();
                    this.chartData.datasets[1].data.shift();
                }
                await this.$nextTick(() => {
                    const chartInstance = this.$refs.chartRef?.chart;
                    if (chartInstance) {
                        chartInstance.data.labels.push(this.isChartLiveCount+1);
                        chartInstance.data.datasets[0].data = this.chartData.datasets[0].data;
                        chartInstance.data.datasets[1].data = this.chartData.datasets[1].data;
                        chartInstance.update();
                    }
                });
                this.isChartLiveCount++;
                if(this.isChartLiveCount < 15 && this.isChartLive) {
                    setTimeout(this.fetchInternetSpeedData, 100);
                } else {
                    this.isChartLive = false;
                    this.isChartLiveCount = 0;
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                this.isChartLive = false;
                this.isChartLiveCount = 0;
            }
        },
        checkMikrotikStatus: function () {
            const self = this;
            loading();
            let url = BASE_URL + '/admin/users/' + self.user_id + '/server-pppoe-status';
            axios.get(url)
                .then((response) => {
                    self.server_pppoe_status = response.data.data.status;
                })
                .catch(error => {
                    notify(error.response.data.message, 'error');
                })
                .finally(() => {
                    loading(false);
                });
        },
        seeAllPaymentsLink: function () {
            return BASE_URL + '/admin/payments?user_id='+this.user_id;
        },
        applyGraceConfirm: function () {
            confirmModal(this.applyGrace, '', 'Are you sure?', 'Grace Confirmation!');
        },
        applyGrace: function () {
            loading();
            let url = BASE_URL + '/admin/payments/grace-payment';
            const data = {
                user_id : this.user_id,
            }
            axios.post(url , data)
                .then((response) => {
                    notify(response.data.message, 'success');
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

<style scoped>

</style>
