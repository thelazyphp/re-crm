import Vue from 'vue'

export default {
  namespaced: true,

  state: {
    token: localStorage.getItem('token')
  },

  getters: {
    check (state) {
      return !!state.token
    }
  },

  mutations: {
    removeToken (state) {
      state.token = null
      Vue.Http.removeToken()
      localStorage.removeItem('token')
    },

    setToken (state, token) {
      state.token = token
      Vue.Http.setToken(token)
      localStorage.setItem('token', token)
    }
  },

  actions: {
    async signOut ({ commit }) {
      try {
        await Vue.Http.post('/auth/logout')
        commit('removeToken')
        commit('users/setCurrent', null, { root: true })
      } catch (error) {
        //

        console.log(error)
      }
    },

    async signIn ({ commit }, data) {
      try {
        const res = await Vue.Http.post('/auth/login', data)
        commit('setToken', res.data.access_token)
      } catch (error) {
        //

        console.log(error)
      }
    }
  }
}
