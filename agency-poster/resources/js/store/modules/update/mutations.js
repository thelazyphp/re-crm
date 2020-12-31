export default {
    ADD_UPLOAD (state, file) {
        state.uploads.push({
            file,
            status: '',
            progress: 0
        })
    },

    REMOVE_UPLOAD (state, index) {
        state.uploads.splice(index, 1)
    },

    REMOVE_ALL_UPLOADS (state) {
        state.uploads = []
    },

    SET_UPLOAD_STATUS (state, { index, status }) {
        state.uploads[index].status = status
    },

    SET_UPLOAD_PROGRESS (state, { index, progress }) {
        state.uploads[index].progress = progress
    }
}
