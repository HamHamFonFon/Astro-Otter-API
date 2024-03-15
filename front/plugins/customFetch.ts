import { useAuthStore } from '@/store/auth';
export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig()
  const authStore = useAuthStore();

  const $customFetch = (locale = 'en') => $fetch.create({
    baseURL: `${config.public.apiPublicHost}/${config.public.apiVersion}`,
    onRequest({ request, options, error }) {
      const reqHeaders = new Headers(options.headers);

      reqHeaders.set('Content-Type', 'application/json');
      reqHeaders.set('Accept', 'application/json');
      reqHeaders.set('Accept-Language', locale);
      if (authStore.isLoggedIn) {
        reqHeaders.set('Authorization', `Bearer ${authStore.accessToken}`);
        // options.headers = options.headers || {}
        // options.headers.authorization = `Bearer ${authStore.accessToken}`
      }
    },
    onResponseError({ response }) {
    }
  });

  nuxtApp.hook('i18n:beforeLocaleSwitch', ({ newLocale }) => {
    $customFetch(newLocale);
  })

  return {
    provide: {
      customFetch: $customFetch
    }
  }
});
