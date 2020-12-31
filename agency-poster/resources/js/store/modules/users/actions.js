import * as UsersService from '../../../services/users.service'

export default {
    getCurrent ({ commit }) {
        return new Promise((resolve, reject) => {
            UsersService.getCurrent()
                .then(user => {
                    commit('SET_CURRENT', user)
                    return resolve()
                })
                .catch(error => reject(error))
        })
    }
}
