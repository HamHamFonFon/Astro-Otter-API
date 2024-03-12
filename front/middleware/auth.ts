import { defineNuxtRouteMiddleware } from 'nuxt/app';
import { login, refreshToken } from '~/composables/loginOrRefresh';
import { useAuthStore } from '~/store/auth';

export default defineNuxtRouteMiddleware(async () => {
  const authStore = useAuthStore();
  const timestamp: number = new Date().getTime();
  if (authStore.isLoggedIn) {
    const expireTokenDate: number | null = authStore.getJwtExp.exp ?? null;
    if (expireTokenDate && expireTokenDate > timestamp) {
      localStorage.clear();
      await refreshToken();
    } else {
      await login();
    }
  } else {
    await login();
  }
});
