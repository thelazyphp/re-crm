require('./bootstrap');

import Vue from 'vue';
import axios from 'axios';
import './mixins';
import store from './store';
import router from './router';
import App from './components/App.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost/castro-blog';

new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app');
