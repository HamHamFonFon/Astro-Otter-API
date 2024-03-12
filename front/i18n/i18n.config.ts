import { en as vuetifyEn, fr as vuetifyFr } from 'vuetify/locale'
import enCustom from '@/i18n/locales/en.json';
import frCustom from '@/i18n/locales/fr.json';
import { createI18n } from 'vue-i18n'

const messages = {
  en: {
    $vuetify: vuetifyEn,
    ...enCustom
  },
  fr: {
    $vuetify: vuetifyFr,
    ...frCustom
  }
};

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  globalInjection: true,
  messages
});

export default i18n;
