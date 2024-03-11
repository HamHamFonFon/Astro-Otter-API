import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app';
import {RouteLocationNormalized} from "vue-router";

export default defineNuxtRouteMiddleware(async (to: RouteLocationNormalized, from: RouteLocationNormalized) => {
  if (['/'].includes(to.path)) {
    return navigateTo('/home');
  }
});
