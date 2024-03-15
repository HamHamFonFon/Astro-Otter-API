import { defineStore } from 'pinia';

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
      const config = useRuntimeConfig()
      try {
        const requestBody: string = JSON.stringify({
          'email': login,
          'password': password,
        });

        const requestHeaders: {Accept: string, 'Content-Type': string} = {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }

        const {
          data,
          error,
          status
        }: any = await useAsyncData(
          'item',
          async () => await $fetch(
            `${config.public.apiPublicHost}/auth`,
            {
              method: 'POST',
              body: requestBody,
              headers: requestHeaders
            }
          )
        );

        if (null !== error.value) {
          return false;
        }

        if ('success' === status.value) {
          const { token, refresh_token } = data.value;
          this.accessToken = token;
          this.refreshToken = refresh_token;
          return true;
        }
        return false;
      } catch (error) {
        return false;
      }
    },
    async fetchRefreshToken(refreshToken: string | null): Promise<boolean> {
      try {
        const config = useRuntimeConfig()
        const requestBody: {refresh_token: string | null} = {
          'refresh_token': refreshToken
        }
        const requestHeaders: {Accept: string, 'Content-Type': string} = {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }

        const {
          data,
          error,
          status
        }: any = await useAsyncData(
          'item',
          async () => await $fetch(
            `${config.public.apiPublicHost}/refresh/token`,
            {
              method: 'POST',
              body: requestBody,
              headers: requestHeaders
            }
          )
        );

        if (null !== error.value) {
          return false;
        }

        if ('success' === status.value) {
          const { token } = data.value;
          this.accessToken = token;
          return true;
        }
        return false;
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


