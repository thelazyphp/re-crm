import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './mixins'
import axios from 'axios'

Vue.config.productionTip = false

axios.defaults.withCredentials = true
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL

axios.interceptors.request.use(config => {
  if (store.state.authToken !== null) {
    config.headers['Authorization'] = `Bearer ${store.state.authToken}`
  }

  return config
}, error => {
  return Promise.reject(error)
})

axios.interceptors.response.use(response => {
  return response
}, error => {
  if (error.response.status === 401) {
    router.push('/sign-in')
  }

  return Promise.reject(error)
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
