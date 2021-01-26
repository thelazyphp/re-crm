import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import DefaultLayout from '../components/layouts/Default.vue';
import AuthLayout from '../components/layouts/Auth.vue';
import Photos from '../views/Photos.vue';
import Posts from '../views/Posts.vue';
import Categories from '../views/Categories.vue';
import NotFound from '../views/NotFound.vue';
import Login from '../views/Login.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        redirect: '/photos',
        component: DefaultLayout,
        children: [
            {
                path: 'photos',
                name: 'photos',
                component: Photos,
                meta: {
                    auth: true,
                    pageTitle: 'Фото'
                }
            },
            {
                path: 'posts',
                name: 'posts',
                component: Posts,
                meta: {
                    auth: true,
                    pageTitle: 'Посты'
                }
            },
            {
                path: 'categories',
                name: 'categories',
                component: Categories,
                meta: {
                    auth: true,
                    pageTitle: 'Категории'
                }
            },
            {
                path: '404',
                name: '404',
                component: NotFound,
                meta: {
                    auth: true,
                    pageTitle: 'Страница не найдена'
                }
            }
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
        ]
    },
    {
        path: '*',
        redirect: '/404'
    }
];

const router = new VueRouter({
    base: '/castro-blog/admin/',
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.auth && !store.state.isAuth) {
        next('/login');
    }

    next();
});

router.beforeEach(async (to, from, next) => {
    if (store.state.isAuth && !store.state.user) {
        await store.dispatch('fetchUser');
        next();
    }

    next();
});

router.beforeEach((to, from, next) => {
    if (to.meta.pageTitle) {
        Vue.nextTick(function () {
            document.title = `${to.meta.pageTitle} | Castro Blog`;
        });
    }

    next();
});

export default router;
