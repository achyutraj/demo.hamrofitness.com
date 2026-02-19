require('./bootstrap');

import Alpine from 'alpinejs';
import axios from 'axios';
import _ from 'lodash';
import KhaltiCheckout from 'khalti-checkout-web';

window.Alpine = Alpine;
window.axios = axios;
window._ = _;
window.KhaltiCheckout = KhaltiCheckout;

Alpine.start();
