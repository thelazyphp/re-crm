import Http from './Http'
import store from '../store'
import User from '../models/User'

function _setAuthData (data) {
    localStorage.setItem('access_token', data.access_token)
}

export function removeAuthData () {
    localStorage.removeItem('access_token')
    store.commit('users/SET_CURRENT', new User())
}

export function getAccessToken () {
    return localStorage.getItem('access_token')
}

export function makeLogin (username, password) {
    return new Promise((resolve, reject) => {
        const data = {
            username,
            password
        }

        new Http().post('/auth/login', data)
            .then(response => {
                _setAuthData(response.data)
                return resolve(response)
            })
            .catch(error => reject(error))
    })
}

export function makeLogout () {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).post('/auth/logout')
            .then(response => {
                removeAuthData()
                return resolve(response)
            })
            .catch(error => reject(error))
    })
}

export function makeRegister (first_name, last_name, email, password, password_confirmation) {
    return new Promise((resolve, reject) => {
        const data = {
            first_name,
            last_name,
            email,
            password,
            password_confirmation
        }

        new Http().post('/auth/register', data)
            .then(response => {
                return resolve(response)
            })
            .catch(error => reject(error))
    })
}
