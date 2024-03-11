import { en as vuetifyEn, fr as vuetifyFr } from 'vuetify/locale'
import enCustom from '@/i18n/locales/en.json';
import frCustom from '@/i18n/locales/fr.json';

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

export default defineI18nConfig(() => ({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  globalInjection: true,
  messages
}));
