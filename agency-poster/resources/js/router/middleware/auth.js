import store from '../../store'

export default function (to, from, next) {
    if (to.matched.some(record => record.meta.auth)) {
        if (! store.state.users.current.id) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
}
