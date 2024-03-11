import {defineNuxtPlugin} from 'nuxt/app';

import 'vuetify/dist/vuetify.min.css';
import {createVuetify} from 'vuetify/lib';
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import * as labs from "vuetify/labs/components";
import '@mdi/font/css/materialdesignicons.css';

export default defineNuxtPlugin(nuxtApp => {
  const AstroOtterLightThemes = {
    dark: false,
    colors: {
      background: "#111b27",
      // surface: "#1E293B",
      primary: '#1B2A32', //'#273E4B',
      secondary: '#253A46',

      appBarColor: '#1B2A32',
      grey: '#a7b6bf', //'#e9e9e9',
      green: '#1ed760'
    },
    theme: {
      grey: '#ff0000' //'#e9e9e9',
    }
  };

  nuxtApp.vuetify = createVuetify({
    components: {
      ...components,
      ...labs,
    },
    directives,
    theme: {
      themes: {
        light: AstroOtterLightThemes
      }
    },
    icons: {
      defaultSet: "mdi",
      aliases,
      sets: {
        mdi
      },
    },
    defaults :{
      VRadioGroup: {
        color: '#1ed760',
      }
    },
    options: {
      customProperties: true
    }
  });
});
