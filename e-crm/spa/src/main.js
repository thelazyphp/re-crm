import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './mixins'
import axios from 'axios'
import Qs from 'qs'

Vue.config.productionTip = false

axios.defaults.paramsSerializer = params => {
  return Qs.stringify(
    params, {arrayFormat: 'brackets'}
  )
}

axios.defaults.params = {
  lang: process.env.VUE_APP_LOCALE
}

axios.defaults.withCredentials = true
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL

if (store.getters.authenticated) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${store.state.authToken}`
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
