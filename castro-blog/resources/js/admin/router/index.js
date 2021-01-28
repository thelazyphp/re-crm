import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import AuthLayout from '../components/layouts/Auth.vue';
import DefaultLayout from '../components/layouts/Default.vue';
import Login from '../views/Login.vue';
import NotFound from '../views/NotFound.vue';
import Blog from '../views/Blog.vue';
import Photos from '../views/Photos.vue';
import Posts from '../views/posts';
import PostsEdit from '../views/posts/Edit.vue';
import PostsCreate from '../views/posts/Create.vue';
import Categories from '../views/categories';
import CategoriesEdit from '../views/categories/Edit.vue';
import CategoriesCreate from '../views/categories/Create.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        redirect: '/blog',
        component: DefaultLayout,
        children: [
            {
                path: 'blog',
                name: 'blog',
                component: Blog,
                meta: {
                    auth: true,
                    pageTitle: 'Блог'
                }
            },
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
                path: 'posts/create',
                name: 'posts.create',
                component: PostsCreate,
                meta: {
                    auth: true,
                    pageTitle: 'Создать пост'
                }
            },
            {
                path: 'posts/:id/edit',
                name: 'posts.edit',
                component: PostsEdit,
                meta: {
                    auth: true,
                    pageTitle: 'Редактировать пост'
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
