/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vuex from 'vuex';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import VueBarcodeScanner from 'vue-barcode-scanner'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import VueSwal from 'vue-swal';
Vue.use(VueSwal);

Vue.use(require('vue-resource'));

Vue.component('v-select', vSelect)

Vue.use(VueToast);

window.Vuex = Vuex;
Vue.use(Vuex);

let options = {
    sound: true, // default is false
    soundSrc: '/checkout.mp3', // default is blan
  }

Vue.use(VueBarcodeScanner, options)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('product-attributes', require('./components/ProductAttributes.vue').default);
Vue.component('attribute-values', require('./components/AttributeValues.vue').default);
Vue.component('search-autocomplete', require('./components/SearchComponent.vue').default);
Vue.component('pos-module', require('./components/pos/index.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading'));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './store.js';

const app = new Vue({
    el: '#app',
    store: new Vuex.Store(store)
});
