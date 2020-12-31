import 'bootstrap'
import Vue from 'vue'
import App from './App'
import store from './store'
import router from './router'
import Http from './plugins/Http'

Vue.config.productionTip = false

Vue.use(Http, {
  baseURL: 'http://185.66.68.97/facebook-feed/api/v1'
})

if (store.state.auth.token) {
  Vue.Http.setToken(store.state.auth.token)
}

new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')
