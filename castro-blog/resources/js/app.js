import Vue from 'vue';
import store from './store';
import router from './router';
import App from './components/App.vue';

export default new Vue({
    store,
    router,
    render: h => h(App)
});
