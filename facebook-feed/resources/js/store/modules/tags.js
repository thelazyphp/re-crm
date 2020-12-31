import Vue from 'vue'

export default {
  namespaced: true,

  state: {
    items: []
  },

  getters: {
    queryParam (state, getters) {
      return getters.text.join(',')
    },

    text (state) {
      return state.items.map(item => item.text)
    }
  },

  mutations: {
    setItems (state, items) {
      state.items = items
    },

    addItem (state, item) {
      state.items.push(item)
    },

    removeItem (state, index) {
      state.items.splice(index, 1)
    }
  },

  actions: {
    async fetch ({ commit }) {
      try {
        const res = await Vue.Http.get('/tags')
        commit('setItems', res.data)
      } catch (error) {
        //

        console.log(error)
      }
    },

    async remove({ state, commit }, index) {
      const id = state.items[index].id

      try {
        await Vue.Http.delete(`/tags/${id}`)
        commit('removeItem', index)
      } catch (error) {
        //

        console.log(error)
      }
    },

    async create ({ commit }, text) {
      try {
        const res = await Vue.Http.post('/tags', { text })
        commit('addItem', res.data)
      } catch (error) {
        //

        console.log(error)
      }
    }
  }
}
