import i18n from '../../lang'
import store from '../../store'
import * as AuthService from '../../services/auth.service'

export default function (to, from, next) {
    if (AuthService.getAccessToken() && ! store.state.users.current.id) {
        store.dispatch('users/getCurrent')
            .catch(error => {
                console.log(error)
                store.commit('alert/SHOW', {
                    type: 'danger',
                    message: i18n.t('errors.get_current_user_error')
                })
            })
            .finally(() => next())
    } else {
        next()
    }
}
