import {defineStore} from 'pinia';
import { AuthWs } from '@/composables/api/auth';
import { jwtParser } from '@/composables/jwtParser';

interface AuthState {
  accessToken: string | null,
  refreshToken: string | null
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => {
    return {
      accessToken: null,
      refreshToken: null
    }
  },

  actions: {
    async fetchLogin(): Promise<boolean> {
      try {
        const wsResponse = await AuthWs.GET_LOGIN();
        const {jwtToken, refreshToken} = wsResponse;

        this.$patch({
          accessToken: jwtToken,
          refreshToken: refreshToken,
        });

        localStorage.setItem('jwtToken', jwtToken)
        return true;
      } catch (error) {
        return false;
      }
    },
    async fetchRefreshToken(): Promise<boolean> {
      try {
        const wsResponse = await AuthWs.GET_REFRESH(this.accessToken)
        const {jwtToken} = wsResponse;
        localStorage.setItem('jwtToken', jwtToken)
        this.$patch({
          accessToken: jwtToken,
        });

        return true;
      } catch (error) {
        return false;
      }
    }
  },

  getters: {
    isLoggedIn: (state) => !!state.accessToken,
    getJwtExp: (state) => jwtParser(state.accessToken)
  },
});


