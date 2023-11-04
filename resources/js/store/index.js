import Vue from 'vue'
import Vuex from 'vuex'
import axios from '../axios'
Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: null,
        dashboard: null,
    },
    mutations: {
        SET_USER(state, payload) {
            state.user = payload
        },

        SET_DASHBOARD(state, payload) {
            state.dashboard = payload
        },
    },
    actions: {
        setUser({ commit }, payload) {
            commit('SET_USER', payload)
        },

        getDashboardData({ commit }) {
            axios.get('/api/dashboard').then(res => {
                commit('SET_DASHBOARD', res.data)
            })
        }
    },
    getters: {
        user: state => {
            return state.user
        },

        dashboard: state => {
            return state.dashboard
        },

        hasRole: state => role => {
            if (!state.user)
                return false;

            if (typeof role == 'object') {
                return role.includes(state.user.role_id)
            }

            return role == state.user.role;
        }
    }
})

export default store
