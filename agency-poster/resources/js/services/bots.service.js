import Http from './Http'
import store from '../store'
import Bot from '../models/Bot'

export function load () {
    new Http({ auth: true }).get('/bots')
        .then(({ data }) => {
            data.forEach(bot => store.commit('bots/ADD', new Bot(bot)))
        })
        .catch(error => {
            console.log(error)
        })
}
