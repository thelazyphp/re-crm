import Vue from 'vue'

export default {
  namespaced: true,

  state: {
    current: null
  },

  mutations: {
    setCurrent (state, user) {
      state.current = user
    }
  },

  actions: {
    async fetchCurrent ({ commit }) {
      try {
        const res = await Vue.Http.get('/users/current')
        commit('setCurrent', res.data)
      } catch (error) {
        //

        console.log(error)
      }
    },

    async removeCurrent ({ commit }) {
      try {
        await Vue.Http.delete('/users/current')
        commit('setCurrent', null)
      } catch (error) {
        //

        console.log(error)
      }
    },

    async updateCurrent ({ commit }, data) {
      try {
        const res = await Vue.Http.put('/users/current', data)
        commit('setCurrent', res.data)
      } catch (error) {
        //

        console.log(error)
      }
    }
  }
}
