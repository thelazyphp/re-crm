import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import DefaultLayout from '../components/layouts/Default.vue';
import AuthLayout from '../components/layouts/Auth.vue';
import Photos from '../views/Photos.vue';
import Posts from '../views/posts';
import Categories from '../views/categories';
import CategoriesCreate from '../views/categories/Create.vue';
import CategoriesEdit from '../views/categories/Edit.vue';
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
                path: 'categories/create',
                name: 'categories.create',
                component: CategoriesCreate,
                meta: {
                    auth: true,
                    pageTitle: 'Создать категорию'
                }
            },
            {
                path: 'categories/:id/edit',
                name: 'categories.edit',
                component: CategoriesEdit,
                meta: {
                    auth: true,
                    pageTitle: 'Редактировать категорию'
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
    } else {
        next();
    }
});

router.beforeEach(async (to, from, next) => {
    if (store.state.isAuth && !store.state.user) {
        await store.dispatch('fetchUser');
        next();
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    if (to.meta.pageTitle) {
        Vue.nextTick(function () {
            document.title = `${to.meta.pageTitle} | Castro Blog`;
        });

        next();
    } else {
        next();
    }
});

export default router;
