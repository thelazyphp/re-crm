import Vue from 'vue'

import i18n from './lang'
import store from './store'
import router from './router'
import Layout from './layout'

import './mixins'
import './bootstrap'

Vue.config.productionTip = false

new Vue({
    i18n,
    store,
    router,
    render: h => h(Layout)
}).$mount('#app')
