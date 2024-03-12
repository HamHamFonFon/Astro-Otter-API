import { defineStore } from 'pinia';
import { AuthWs } from '@/repositories/api/auth';
import { jwtParser } from '~/composables/jwtParser';

interface AuthState {
  accessToken: string | null,
  refreshToken: string | null
}

export const authStore = defineStore('auth', {
  state: (): AuthState => {
    return {
      accessToken: null,
      refreshToken: null
    }
  },

  actions: {
    async fetchLogin({ commit }: any): Promise<boolean> {
      try {
        const wsResponse = await AuthWs.GET_LOGIN();
        const {jwtToken, refreshToken} = wsResponse;

        commit('setAccessToken', jwtToken);
        commit('setRefreshToken', refreshToken)

        localStorage.setItem('jwtToken', jwtToken)
        return true;
      } catch (error) {
        return false;
      }
    },
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
  },

  getters: {
    isLoggedIn: (state: AuthState) => !!state.accessToken,
    getJwtExp: (state: AuthState) => jwtParser(state.accessToken)
  },

  mutations: {
    setAccessToken(state: AuthState, accessToken: string | null): void {
      state.accessToken = accessToken
    },
    setRefreshToken(state: AuthState, refreshToken: string | null): void {
      state.refreshToken = refreshToken
    }
  }
});
