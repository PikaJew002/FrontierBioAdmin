
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vuelidate from 'vuelidate'
import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(Vuelidate);
Vue.use(BootstrapVue);
Vue.component('customers', require('./components/Customers.vue'));
Vue.component('orders', require('./components/Orders.vue'));
Vue.component('products', require('./components/Products.vue'));
Vue.component('items', require('./components/Items.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('order-item', require('./components/OrderItem.vue'));

const app = new Vue({
    el: '#app'
});
