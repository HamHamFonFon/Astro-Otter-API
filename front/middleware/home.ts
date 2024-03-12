import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app';

export default defineNuxtRouteMiddleware(async (from, to) => {
  if (['/'].includes(to.path)) {
    return navigateTo('/home');
  }
});
