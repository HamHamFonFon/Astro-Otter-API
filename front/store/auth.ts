import { AuthWs } from '@/repositories/api/auth';
import { jwtParser } from '~/composables/jwtParser';

export interface AuthState {
  accessToken: string | null,
  refreshToken: string | null
}

const state: AuthState = {
  accessToken: null,
  refreshToken: null
}

const mutations = {
  setAccessToken(state: AuthState, accessToken: string): void {
    state.accessToken = accessToken
  },
  setRefreshToken(state: AuthState, refreshToken: string): void {
    state.refreshToken = refreshToken
  }
}

const actions = {
  async fetchLogin({ commit }: any) {
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
  async fetchRefreshToken({ commit }: any) {
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
  isLoggedIn: (state: AuthState) => !!state.accessToken,
  getJwtExp: (state: AuthState) => jwtParser(state.accessToken)
};

export const authStore = defineStore({
  state,
  mutations,
  actions,
  getters,
}).with({ namespaced: true });
