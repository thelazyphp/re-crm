import Vue from 'vue'

const getIndexById = (items, id) => items.findIndex(item => item.id == id)
const getItemyId = (items, id) => items.find(item => item.id == id)

export default {
	namespaced: true,

	state: {
    search: null,
    sort: '-published_at',
		page: 1,
    items: [],
    lastPage: null
	},

	getters: {
		queryParams (state, getters, rootState, rootGetters) {
			let params = {
        page: state.page
      }

      if (state.sort) {
				params['sort'] = state.sort
      }

      if (state.search) {
				params['search'] = state.search
      }

      if (rootGetters['tags/queryParam']) {
        params['tags'] = rootGetters['tags/queryParam']
      }

			return params
		}
	},

	mutations: {
		setSearch (state, search) {
			state.search = search
    },

    setSort (state, sort) {
			state.sort = sort
    },

		setPage (state, page) {
			state.page = page
		},

		incrementPage (state) {
			state.page++
		},

		setItems (state, items) {
			state.items = items
		},

		addItems (state, items) {
			state.items = state.items.concat(items)
    },

		setLastPage (state, lastPage) {
			state.lastPage = lastPage
    },

    favoriteItem(state, id) {
      const index = getIndexById(state.items, id)
      state.items[index].is_favorite = true
    },

    unfavoriteItem(state, id) {
      const index = getIndexById(state.items, id)
      state.items[index].is_favorite = false
    }
	},

	actions: {
		async fetch ({ getters, commit }) {
			try {
				const res = await Vue.Http.get('/posts', {
					params: getters.queryParams
				})

        commit('setItems', res.data.data)
        commit('setLastPage', res.data.meta.last_page)
			} catch (error) {
        //

        console.log(error)
			}
		},

    async fetchNext ({ getters, commit }) {
      commit('incrementPage')

			try {
				const res = await Vue.Http.get('/posts', {
					params: getters.queryParams
				})

        commit('addItems', res.data.data)
        commit('setLastPage', res.data.meta.last_page)
			} catch (error) {
        //

        console.log(error)
			}
    },

    async favorite ({ commit }, id) {
      try {
        await Vue.Http.post(`/favorites/${id}`)
        commit('favoriteItem', id)
        commit('favorites/incrementTotal', null, { root: true })
      } catch (error) {
        //

        console.log(error)
      }
    },

    async unfavorite ({ commit }, id) {
      try {
        await Vue.Http.delete(`/favorites/${id}`)
        commit('unfavoriteItem', id)
        commit('favorites/decrementTotal', null, { root: true })
      } catch (error) {
        //

        console.log(error)
      }
    },

    async toggleFavorite ({ state, dispatch }, id) {
      const item = getItemyId(state.items, id)
      item.is_favorite ? await dispatch('unfavorite', id) : await dispatch('favorite', id)
    }
	}
}
