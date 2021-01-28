import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

import {
    SET_USER,
    SET_IS_AUTH,
} from './mutation-types';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        isAuth: JSON.parse(localStorage.getItem('isAuth'))
    },

    mutations: {
        [SET_USER] (state, user) {
            state.user = user;
        },

        [SET_IS_AUTH] (state, isAuth) {
            state.isAuth = isAuth;
            localStorage.setItem('isAuth', JSON.stringify(isAuth));
        }
    },

    actions: {
        async fetchUser ({ commit }) {
            try {
                const { data } = await axios.get('/api/user');
                commit(SET_USER, data.data);
            } catch (error) {
                //

                console.log(error);
            }
        }
    }
});
