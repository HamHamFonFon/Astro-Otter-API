import { defineNuxtRouteMiddleware } from 'nuxt/app';
import { inject } from 'vue';
const store = inject('store');

import { login, refreshToken } from '@/composables/loginOrRefresh';

export default defineNuxtRouteMiddleware(async () => {
  const timestamp: number = new Date().getTime();
  if (true === store.getters.auth.isLoggedIn) {
    const expireTokenDate: string|null = store.getters.auth.getJwtExp.exp ?? null;
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
