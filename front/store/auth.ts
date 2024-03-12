import { AuthWs } from '@/repositories/api/auth';
import { jwtParser } from '~/composables/jwtParser';
import { Store } from 'vuex';

interface AuthState {
  accessToken: string | null,
  refreshToken: string | null
}

export const state = (): AuthState => ({
  accessToken: null,
  refreshToken: null
})

export const mutations = {
  setAccessToken(state: AuthState, accessToken: string | null): void {
    state.accessToken = accessToken
  },
  setRefreshToken(state: AuthState, refreshToken: string | null): void {
    state.refreshToken = refreshToken
  }
}

export const actions = {
  async fetchLogin({ commit }: any): Promise<boolean> {
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
  async fetchRefreshToken({ commit }: any): Promise<boolean> {
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

export const getters = {
  isLoggedIn: (state: AuthState) => !!state.accessToken,
  getJwtExp: (state: AuthState) => jwtParser(state.accessToken)
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};
