import { defineNuxtPlugin } from 'nuxt/app';

import authStore from "~/store/auth";
import messageStore from "~/store/message";

interface StoreMap {
  auth: typeof authStore;
  message: typeof messageStore;
  // Add more stores here if needed
}

export default defineNuxtPlugin((nuxtApp) => {
  const stores: StoreMap = {
    auth: authStore,
    message: messageStore
  };

  nuxtApp.provide('stores', stores);
});
