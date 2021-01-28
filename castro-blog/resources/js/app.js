import Vue from 'vue';
import axios from 'axios';
import store from './store';
import router from './router';
import App from './components/App.vue';

Vue.config.productionTip = false;

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost/castro-blog';

export default new Vue({
    store,
    router,
    render: h => h(App)
});
