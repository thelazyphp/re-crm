require('./bootstrap');

import 'bootstrap';
import './components';
import Vue from 'vue';
import axios from 'axios';
import router from './router';
import App from './components/App.vue';

Vue.config.productionTip = false;

axios.defaults.baseURL = window.config.base;

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');
