import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'

import {
  SET_PAGE_TITLE
} from '../store/mutation-types'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/dasboard',
    component: () => import('../components/layouts/Default.vue'),
    children: [
      {
        path: 'dasboard',
        component: () => import(/* webpackChunkName: "dasboard" */ '../views/Dasboard.vue'),
        meta: {
          requiresAuth: true,
          pageTitle: 'Панель управления'
        }
      }
    ]
  },
  {
    path: '/',
    component: () => import('../components/layouts/Auth.vue'),
    children: [
      {
        path: 'login',
        component: () => import(/* webpackChunkName: "auth" */ '../views/auth/Login.vue'),
        meta: {
          pageTitle: 'Войти'
        }
      },
      {
        path: 'register',
        component: () => import(/* webpackChunkName: "auth" */ '../views/auth/Register.vue'),
        meta: {
          pageTitle: 'Создать аккаунт'
        }
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    if (!store.getters.authenticated) {
      next('/login')
    }
  }

  next()
})

router.beforeEach((to, from, next) => {
  if (to.meta.pageTitle) {
    store.commit(
      SET_PAGE_TITLE, to.meta.pageTitle
    )
  }

  next()
})

router.beforeEach((to, from, next) => {
  if (store.getters.authenticated) {
    if (!store.state.currentUser) {
      store.dispatch('fetchCurrentUser').then(next)
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
