import Vue from 'vue'
import Router from 'vue-router'

import routes from './routes'
import { ROUTER_BASE } from '../.env'

// import middleware
import initCurrentUser from './middleware/init.current.user'
import auth from './middleware/auth'

Vue.use(Router)

const router = new Router({
    base: ROUTER_BASE,
    mode: 'history',
    routes
})

router.beforeEach(initCurrentUser)
router.beforeEach(auth)

export default router
