import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './mixins'
import axios from 'axios'

Vue.config.productionTip = false

axios.defaults.withCredentials = true
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
