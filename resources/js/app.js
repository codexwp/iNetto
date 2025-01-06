import './bootstrap';
import './helper'

import 'sweetalert2/dist/sweetalert2.min.css';

import { createApp } from 'vue';
import UserDetailComponent from './components/UserDetailComponent.vue';
import UserDetailComponent2 from './components/UserDetailComponent2.vue';
import SellerDetailComponent from './components/SellerDetailComponent.vue';
import SystemSettingComponent from "./components/SystemSettingComponent.vue";
import SmsGatewayComponent from "./components/SmsGatewayComponent.vue";
import PaymentGatewayComponent from "./components/PaymentGatewayComponent.vue";
import SendSmsComponent from "./components/SendSmsComponent.vue";
import BulkPaymentComponent from "./components/BulkPaymentComponent.vue";
import BulkPaymentComponent2 from "./components/BulkPaymentComponent2.vue";
import BillPayComponent from "./components/BillPayComponent.vue";

const app = createApp({});
app.component('user-detail-component', UserDetailComponent);
app.component('user-detail-component-2', UserDetailComponent2);
app.component('seller-detail-component', SellerDetailComponent);
app.component('system-setting-component', SystemSettingComponent);
app.component('sms-gateway-component', SmsGatewayComponent);
app.component('payment-gateway-component', PaymentGatewayComponent);
app.component('send-sms-component', SendSmsComponent);
app.component('bulk-payment-component', BulkPaymentComponent);
app.component('bulk-payment-component2', BulkPaymentComponent2);
app.component('bill-pay-component', BillPayComponent);

app.mount("#app");
