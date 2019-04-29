
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
window.Vue = require('vue');
import App from './app.vue'
import router from './router'
import iView from 'iview';
import store from './store'
import 'iview/dist/styles/iview.css';
import axios from './libs/http'
import highlight from './libs/highlight'
// 导入iview
Vue.use(iView);
Vue.use(highlight);
window.axios = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
