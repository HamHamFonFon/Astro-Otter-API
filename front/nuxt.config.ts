// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config';

export default defineNuxtConfig({
  devtools: { enabled: true },
  typescript: {
    typeCheck: true
  },
  middleware: [
    'auth', 'home'
  ],
  plugins: [
    '~/plugins/vuex',
    './plugins/vuetify'
  ],
  runtimeConfig: {
    // astrobin
    astrobinApiKey: '',
    astrobinApiSecret: '',
    // login/pwd API
    apiLogin: '',
    apiPwd: '',
    //

    // Values exposed public side
    public: {
      mercurePublicUrl: 'https://mercure.astro-otter.space/.well-known/mercure'
    }
  }
})
