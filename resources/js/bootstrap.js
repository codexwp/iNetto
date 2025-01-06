import mitt from 'mitt'
window.bus = mitt();

import.meta.glob([
    '../../images/**',
]);

import moment from "moment";
window.moment = moment;

import Swal from "sweetalert2";
window.Swal = Swal;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['CSRF-TOKEN'] = document.getElementsByName("csrf-token")[0].getAttribute('content');

window.BASE_URL = document.getElementsByName("base-url")[0].getAttribute('content');

