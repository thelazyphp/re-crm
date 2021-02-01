import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import Login from '../views/auth/Login.vue';
import Register from '../views/auth/Register.vue';
import AuthLayout from '../components/layouts/auth';
import Dashboard from '../views/Dashboard.vue';
import Props from '../views/Props.vue';
import Import from '../views/Import.vue';
import DefaultLayout from '../components/layouts/default';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        redirect: '/dashboard',
        component: DefaultLayout,
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: Dashboard,
                meta: {
                    auth: true,
                    pageTitle: 'Дашборд'
                }
            },
            {
                path: 'props',
                name: 'props',
                component: Props,
                meta: {
                    auth: true,
                    pageTitle: 'Записи'
                }
            },
            {
                path: 'import',
                name: 'import',
                component: Import,
                meta: {
                    auth: true,
                    pageTitle: 'Импорт'
                }
            },
        ]
    },
    {
        path: '/',
        component: AuthLayout,
        children: [
            {
                path: 'login',
                name: 'login',
                component: Login,
                meta: {
                    pageTitle: 'Войти'
                }
            },
            {
                path: 'register',
                name: 'register',
                component: Register,
                meta: {
                    pageTitle: 'Создать аккаунт'
                }
            },
        ]
    },
    {
        path: '*',
        redirect: '/404'
    }
];

const router = new VueRouter({
    base: '/nca/',
    routes,
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    if (to.meta.auth && !store.state.isAuth) {
        next({
            name: 'login'
        });
    }

    next();
});

router.beforeEach(async (to, from, next) => {
    if (to.meta.auth && !store.state.user) {
        await store.dispatch('fetchUser');
        next();
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    if (to.meta.pageTitle) {
        Vue.nextTick(() => {
            document.title = `${to.meta.pageTitle} | База БТИ`;
        });
    }

    next();
});

export default router;
