import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        //
    },

    state: {
        user: null,
        isAuth: JSON.parse(localStorage.getItem('isAuth'))
    },

    mutations: {
        ['SET_USER'] (state, user) {
            state.user = user;
        },

        ['SET_IS_AUTH'] (state, isAuth) {
            state.isAuth = isAuth;
            localStorage.setItem('isAuth', JSON.stringify(isAuth));
        }
    },

    actions: {
        async fetchUser ({ commit }) {
            const { data } = await axios.get('/api/user');
            commit('SET_USER', data.data);
        }
    }
});
