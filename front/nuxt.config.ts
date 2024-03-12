// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config';

// Plugins
// import axiosApi from './plugins/axiosApi';
// import vuetify from './plugins/vuetify';

export default defineNuxtConfig({
  devtools: { enabled: true },
  typescript: {
    typeCheck: true
  },
  router: {
    middleware: ['auth'],
  },
  plugins: [
    // vuetify,
    // axiosApi
  ],
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/i18n'
  ],
  i18n: {
    vueI18n: './i18n/i18n.config.ts'
  },
  runtimeConfig: {
    // astrobin
    astrobinApiKey: '',
    astrobinApiSecret: '',
    // login/pwd API
    apiLogin: '',
    apiPwd: '',

    // Values exposed public side
    public: {
      apiPublicHost: 'https://api.astro-otter.space',
      apiVersion: 'v2',
      astrobinHost: 'https://www.astrobin.com',
      astrobinApiUrl: 'https://www.astrobin.com/api/v1/',
      mercurePublicUrl: 'https://mercure.astro-otter.space/.well-known/mercure'
    }
  }
})
