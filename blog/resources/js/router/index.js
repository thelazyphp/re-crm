import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

Vue.use(VueRouter);

const router = new VueRouter({
    base: window.config.base,
    mode: 'history',
    routes
});

export default router;
