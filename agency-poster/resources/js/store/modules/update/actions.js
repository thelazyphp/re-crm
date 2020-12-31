import * as UpdateService from '../../../services/update.service'

export default {
    uploadFile ({ state, commit }, index) {
        const onUploadProgress = function (event) {
            if (event.lengthComputable) {
                commit('SET_UPLOAD_PROGRESS', {
                    index,
                    progress: Math.round(
                        (event.loaded / event.total) * 100
                    )
                })
            } else {
                commit('SET_UPLOAD_PROGRESS', {
                    index,
                    progress: 100
                })
            }
        }

        commit('SET_UPLOAD_STATUS', {
            index,
            status: 'uploading'
        })

        UpdateService.uploadFile(state.uploads[index].file, onUploadProgress)
            .then(response => {
                commit('SET_UPLOAD_STATUS', {
                    index,
                    status: 'updating'
                })

                commit('SET_UPLOAD_PROGRESS', {
                    index,
                    progress: 0
                })

                const contentLocation = response.headers['content-location']

                UpdateService.checkUpdateStatus(contentLocation)
                    .then(response => {
                        let toManyRequests = false

                        const interval = setInterval(function () {
                            if (! toManyRequests) {
                                UpdateService.checkUpdateStatus(contentLocation)
                                    .then(response => {
                                        toManyRequests = false

                                        if (response.data.status == 'updated') {
                                            clearInterval(interval)

                                            commit('SET_UPLOAD_STATUS', {
                                                index,
                                                status: ''
                                            })
                            
                                            commit('SET_UPLOAD_PROGRESS', {
                                                index,
                                                progress: 0
                                            })
                                        } else {
                                            commit('SET_UPLOAD_PROGRESS', {
                                                index,
                                                progress: Math.round(
                                                    (response.data.updated / response.data.total) * 100
                                                )
                                            })
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

    uploadAllFiles ({ state, dispatch }) {
        for (let index in state.uploads) {
            dispatch('uploadFile', index)
        }
    }
}
