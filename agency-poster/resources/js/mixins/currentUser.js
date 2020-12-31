import store from '../store'

export default {
    computed: {
        currentUser () {
            return store.state.users.current
        }
    }
}
