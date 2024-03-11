import {RouteLocationNormalized} from "vue-router";
import {login} from '@/composables/loginOrRefresh';

import { inject } from 'vue';
const store = inject('store');
import { defineNuxtRouteMiddleware } from 'nuxt/app';

export default defineNuxtRouteMiddleware(async (to: RouteLocationNormalized, from: RouteLocationNormalized) => {
  const timestamp: number = new Date().getTime();
  if (true === store.getters["auth/isLoggedIn"]) {
    const expireTokenDate = store.getters["auth/getJwtExp"].exp ?? null;
    if (expireTokenDate && expireTokenDate > timestamp) {
      localStorage.clear();
      //await refreshToken();
      await login();
    } else {
      await login();
    }
  } else {
    await login();
  }
});
