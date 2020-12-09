import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'

import {
  SET_PAGE_TITLE
} from '../store/mutation-types'

Vue.use(VueRouter)

const routes = [
  {
    path: '/sign-in',
    component: () => import(/* webpackChunkName: "sign-in" */ '../views/SignIn.vue'),
    meta: {
      pageTitle: 'Войти'
    }
  },
  {
    path: '/sign-up',
    component: () => import(/* webpackChunkName: "sign-up" */ '../views/SignUp.vue'),
    meta: {
      pageTitle: 'Создать аккаунт'
    }
  },
  {
    path: '*',
    component: () => import(/* webpackChunkName: "not-found" */ '../views/NotFound.vue'),
    meta: {
      pageTitle: 'Страница не найдена'
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
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
  if (to.meta.requiresAuth) {
    if (!store.getters.authenticated) {
      next('/sign-in')
    }
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
