import { AuthWs } from '@/repositories/api/auth';
import { jwtParser } from '@/services/jwtParser';

const state = () => {
    return {
        accessToken: null,
        refreshToken: null,
    }
}

const mutations = {
    setAccessToken(state, accessToken) {
        state.accessToken = accessToken
    },
    setRefreshToken(state, refreshToken) {
        state.refreshToken = refreshToken
    }
}

const actions = {
    async fetchLogin({ commit }) {
        try {
            const wsResponse = await AuthWs.GET_LOGIN();
            const { jwtToken, refreshToken } = wsResponse;

            commit('setAccessToken', jwtToken);
            commit('setRefreshToken', refreshToken)

            localStorage.setItem('jwtToken', jwtToken)
            return true;
        } catch (error) {
            return false;
        }
    },

    /**
     *
     * @param commit
     * @param getters
     * @returns {Promise<boolean>}
     */
    async fetchRefreshToken({ commit }) {
        try {
            const wsResponse = await AuthWs.GET_REFRESH(state.refreshToken)
            const { jwtToken } = wsResponse.data;
            commit('setAccessToken', jwtToken);

            return true;
        } catch (error) {
            return false;
        }
    }
}

const getters = {
    isLoggedIn: (state) => !!state.accessToken,
    getJwtExp: (state) => jwtParser(state.accessToken)
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
};