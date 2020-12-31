export default {
    ADD (state, bot) {
        state.items.push(bot)
    },

    SET_ACTIVE (state, { index, active }) {
        state.items[index].active = active
    },

    SET_RUN_AT (state, { index, runAt }) {
        state.items[index].run_at = runAt
    },

    SET_RUNNING (state, { index, running }) {
        state.items[index].running = running
    }
}
