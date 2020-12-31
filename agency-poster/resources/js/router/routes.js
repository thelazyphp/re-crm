// import pages
import LoginPage from '../pages/LoginPage'
import RegisterPage from '../pages/RegisterPage'
import HomePage from '../pages/HomePage'
import UpdatePage from '../pages/UpdatePage'
import FeedsPage from '../pages/FeedsPage'
import BotsPage from '../pages/BotsPage'
import LogsPage from '../pages/LogsPage'
import ProfilePage from '../pages/ProfilePage'
import ProfileEditPage from '../pages/ProfileEditPage'
import AccountDeletePage from '../pages/AccountDeletePage'

export default [
    {
        name: 'login',
        path: '/login',
        component: LoginPage
    },
    {
        name: 'register',
        path: '/register',
        component: RegisterPage
    },
    {
        name: 'home',
        path: '/',
        component: HomePage,
        meta: { auth: true }
    },
    {
        name: 'update',
        path: '/update',
        component: UpdatePage,
        meta: { auth: true }
    },
    {
        name: 'feeds',
        path: '/feeds',
        component: FeedsPage,
        meta: { auth: true }
    },
    {
        name: 'bots',
        path: '/bots',
        component: BotsPage,
        meta: { auth: true }
    },
    {
        name: 'logs',
        path: '/logs',
        component: LogsPage,
        meta: { auth: true }
    },
    {
        name: 'profile',
        path: '/profile',
        component: ProfilePage,
        meta: { auth: true }
    },
    {
        name: 'profile.edit',
        path: '/profile/edit',
        component: ProfileEditPage,
        meta: { auth: true }
    },
    {
        name: 'account.delete',
        path: '/account/delete',
        component: AccountDeletePage,
        meta: { auth: true }
    }
]
