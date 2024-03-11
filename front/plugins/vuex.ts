import {createLogger} from 'vuex'
import {defineNuxtPlugin, createStore} from 'nuxt/app';

import {authStore} from "~/store/auth";
import {messageStore} from "~/store/message";

export default defineNuxtPlugin(() => {
  nuxtApp.$store = createStore({
    modules: {
      auth: authStore,
      message: messageStore
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
  });
});
