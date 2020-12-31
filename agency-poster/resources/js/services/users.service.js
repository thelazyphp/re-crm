import Http from './Http'
import store from '../store'
import User from '../models/User'

export function getCurrent () {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).get('/users/current')
            .then(({ data }) => resolve(new User(data)))
            .catch(error => reject(error))
    })
}

export function editProfile (data = {}) {
    return new Promise ((resolve, reject) => {
        const requestData = {}

        if (data.first_name && data.first_name != store.state.users.current.first_name) {
            requestData['first_name'] = data.first_name
        }

        if (data.last_name && data.last_name != store.state.users.current.last_name) {
            requestData['last_name'] = data.last_name
        }

        if (data.email && data.email != store.state.users.current.email) {
            requestData['email'] = data.email
        }

        if (data.password) {
            requestData['password'] = data.password
        }

        if (data.new_password) {
            requestData['new_password'] = data.new_password
        }

        if (data.new_password_confirmation) {
            requestData['new_password_confirmation'] = data.new_password_confirmation
        }

        new Http({ auth: true }).patch('/users/current', requestData)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}

export function deleteAccount () {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).delete('/users/current')
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}
