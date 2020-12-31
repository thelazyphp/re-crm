import Http from './Http'
import store from '../store'
import Feed from '../models/Feed'

export function load () {
    new Http({ auth: true }).get('/feeds')
        .then(({ data }) => {
            data.forEach(feed => {
                store.commit('feeds/ADD', new Feed(feed))
            })
        })
        .catch(error => console.log(error))
}

export function generate (id) {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).post(`/feeds/${id}/generate`)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}

export function checkGenerateStatus (contentLocation) {
    return new Promise((resolve, reject) => {
        new Http({ auth: true }).get(contentLocation)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}
