export default {
    ADD (state, feed) {
        state.items.push(feed)
    },

    UPDATE (state, { index, feed }) {
        state.items[index] = feed
    },

    SET_STATUS (state, { index, status }) {
        state.items[index].status = status
    },

    SET_PROGRESS (state, { index, progress }) {
        state.items[index].progress = progress
    }
}
