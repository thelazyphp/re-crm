import Vue from 'vue'
import Vuex from 'vuex'

// import modules
import bots from './modules/bots'
import feeds from './modules/feeds'
import users from './modules/users'
import alert from './modules/alert'
import update from './modules/update'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        bots,
        feeds,
        users,
        alert,
        update
    }
})
