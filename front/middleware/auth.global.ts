import { defineNuxtRouteMiddleware } from 'nuxt/app';
import { useAuthStore } from '@/store/auth';

export default defineNuxtRouteMiddleware(async (): Promise<void> => {
  const config = useRuntimeConfig()
  const authStore = useAuthStore();
  const timestamp: number = new Date().getTime();

  if (authStore.isLoggedIn) {
    const expireTokenDate: number | null = authStore.getJwtExp.exp ?? null;
    if (null === expireTokenDate) {
      await authStore.fetchLogin(config.apiLogin, config.apiPwd);
    }
    if (expireTokenDate && expireTokenDate > timestamp) {
      await authStore.fetchRefreshToken(authStore.accessToken);
    }

  } else {
    await authStore.fetchLogin(process.env.NUXT_API_LOGIN, process.env.NUXT_API_PWD);
  }
});
