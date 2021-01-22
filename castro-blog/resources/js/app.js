import Vue from 'vue';
import router from './router'
import App from './components/App.vue';

export default new Vue({
    router,
    render: h => h(App)
});
