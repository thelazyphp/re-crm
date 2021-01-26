import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../views/Home.vue';
import Posts from '../views/Posts.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            pageTitle: 'Главная'
        }
    },
    {
        path: '/posts',
        name: 'posts',
        component: Posts,
        meta: {
            pageTitle: 'Посты'
        }
    },
];

const router = new VueRouter({
    base: '/castro-blog/',
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.pageTitle && typeof document !== 'undefined') {
        Vue.nextTick(function () {
            document.title = `${to.meta.pageTitle} | Castro Blog`;
        });
    }

    next();
});

export default router;
