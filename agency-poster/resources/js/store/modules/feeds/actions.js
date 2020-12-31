import Feed from '../../../models/Feed'
import Http from '../../../services/Http'
import * as FeedsService from '../../../services/feeds.service'

export default {
    generate ({ state, commit }, index) {
        FeedsService.generate(state.items[index].id)
            .then(response => {
                commit('SET_STATUS', {
                    index,
                    status: 'generating'
                })

                const contentLocation = response.headers['content-location']

                FeedsService.checkGenerateStatus(contentLocation)
                    .then(response => {
                        let toManyRequests = false

                        const interval = setInterval(function () {
                            if (! toManyRequests) {
                                FeedsService.checkGenerateStatus(contentLocation)
                                    .then(response => {
                                        if (response.data.status == 'generating') {
                                            commit('SET_PROGRESS', {
                                                index,
                                                progress: Math.round(
                                                    (response.data.generated / response.data.total) * 100
                                                )
                                            })
                                        } else {
                                            clearInterval(interval)

                                            commit('SET_STATUS', {
                                                index,
                                                status: ''
                                            })

                                            commit('SET_PROGRESS', {
                                                index,
                                                progress: 0
                                            })

                                            new Http({ auth: true }).get(`/feeds/${state.items[index].id}`)
                                                .then(({ data }) => commit('UPDATE', { index, feed: new Feed(data) }))
                                        }
                                    })
                                    .catch(error => {
                                        if (error.response.status == 429) {
                                            toManyRequests = true
                                        } else {
                                            clearInterval(interval)
                                        }
                                    })
                            }
                        }, response.headers['retry-after'] * 1000)
                    })
            })
            .catch(error => {
                console.log(error)
                //
            })
    },

    generateAll ({ state, dispatch }) {
        for (let index in state.items) {
            dispatch('generate', index)
        }
    }
}
