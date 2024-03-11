import { createStore, createLogger } from 'vuex'
import { defineNuxtPlugin } from 'nuxt/app';

import auth from '@/store/modules/Auth'
import message from "@/store/modules/Message";

export default defineNuxtPlugin(() => {
  const store = createStore({
    modules: {
      auth,
      message
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
  })

  return { provide: { store } };
});
