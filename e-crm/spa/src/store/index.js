import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

import {
  SET_PAGE_TITLE,
  SET_AUTH_TOKEN,
  SET_CURRENT_USER
} from './mutation-types'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    //
  },

  state: {
    pageTitle: process.env.VUE_APP_NAME,
    authToken: localStorage.getItem('authToken'),
    currentUser: null
  },

  getters: {
    authenticated: state => state.authToken !== null
  },

  mutations: {
    [SET_PAGE_TITLE] (state, title) {
      state.pageTitle = title
      Vue.nextTick(() => document.title = `${process.env.VUE_APP_NAME} | ${title}`)
    },

    [SET_AUTH_TOKEN] (state, token) {
      state.authToken = token
    },

    [SET_CURRENT_USER] (state, user) {
      state.currentUser = user
    }
  },

  actions: {
    fetchCurrentUser ({ commit }) {
      return new Promise((resolve, reject) => {
        axios.get('/users/current').then(response => {
          commit(SET_CURRENT_USER, response.data['data'])
          return resolve(response)
        }).catch(error => {
          return reject(error)
        })
      })
    },

    login ({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios.post('/login', data).then(response => {
          commit(SET_AUTH_TOKEN, response.data['access_token'])
          localStorage.setItem('authToken', response.data['access_token'])
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data['access_token']}`
          return resolve(response)
        }).catch(error => {
          return reject(error)
        })
      })
    },

    logout ({ commit }) {
      return new Promise((resolve, reject) => {
        axios.post('/logout').then(response => {
          commit(SET_AUTH_TOKEN, null)
          commit(SET_CURRENT_USER, null)
          localStorage.removeItem('authToken')
          delete axios.defaults.headers.common['Authorization']
          return resolve(response)
        }).catch(error => {
          return reject(error)
        })
      })
    },

    register ({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios.post('/register', data).then(response => {
          commit(SET_AUTH_TOKEN, response.data['access_token'])
          localStorage.setItem('authToken', response.data['access_token'])
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data['access_token']}`
          return resolve(response)
        }).catch(error => {
          return reject(error)
        })
      })
    }
  }
})
