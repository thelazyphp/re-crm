import Vue from 'vue'
import store from '../store'
import routes from './routes'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: '/facebook-feed/',
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters['auth/check']) {
    next('/sign-in')
  } else {
    next()
  }
})

router.beforeEach((to, from, next) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | Facebook Feed`
    next()
  } else {
    next()
  }
})

router.beforeEach(async (to, from, next) => {
  if (store.getters['auth/check'] && !store.state.users.current) {
    await store.dispatch('users/fetchCurrent')
    next()
  } else {
    next()
  }
})

export default router
