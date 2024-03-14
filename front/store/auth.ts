import {defineStore} from 'pinia';
import axios, {type AxiosResponse} from "axios";

interface AuthState {
  accessToken: string | null,
  refreshToken: string | null
}

interface AuthGetters {
}

interface AuthActions {
}
// <
//   'auth',
//   AuthState,
//   AuthGetters,
// AuthActions
// >
export const useAuthStore = defineStore('auth', {
  state: (): AuthState => {
    return {
      accessToken: null,
      refreshToken: null
    }
  },

  actions: {
    async fetchLogin(login: string | undefined, password: string | undefined): Promise<boolean> {
      try {
        const requestBody: string = JSON.stringify({
          'email': login,
          'password': password,
        });

        const headers: {Accept: string, 'Content-Type': string} = {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }

        const apiPublicHost = 'https://api.astro-otter.space';
        const wsResponse: AxiosResponse<any> = await axios.post(apiPublicHost + '/auth', requestBody, {headers});
        if (200 !== wsResponse.status) {
          throw new Error(wsResponse.statusText);
        }
        const jwtToken = wsResponse.data.token;
        const refreshToken = wsResponse.data.refresh_token;

        this.$patch({
          accessToken: jwtToken,
          refreshToken: refreshToken,
        });

        return true;
      } catch (error) {
        return false;
      }
    },
    async fetchRefreshToken(refreshToken: string | null): Promise<boolean> {
      try {

        const requestBody: {refresh_token: string | null} = {
          'refresh_token': refreshToken
        }
        const headers: {Accept: string, 'Content-Type': string} = {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }

        // @todo : use nuxt config variable
        const apiPublicHost = 'https://api.astro-otter.space';
        const response: AxiosResponse<any> = await axios.post(apiPublicHost + '/token/refresh', requestBody, {headers});
        if (200 !== response.status) {
          throw new Error(response.statusText);
        }
        const jwtToken:string = response.data.token;
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


