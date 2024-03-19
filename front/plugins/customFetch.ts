import { useAuthStore } from '@/store/auth';
import type {RuntimeConfig} from "nuxt/schema";

export default defineNuxtPlugin((nuxtApp) => {

  const config: RuntimeConfig = useRuntimeConfig()
  const authStore = useAuthStore();

  const $customFetch = (locale = 'en') => $fetch.create({
    baseURL: `${config.public.apiPublicHost}/${config.public.apiVersion}`,
    onRequest({ request, options, error}) {
      const customHeaders = options?.headers ? new Headers(options.headers) : new Headers();
      customHeaders.set('Content-Type', 'application/json');
      customHeaders.set('Accept', 'application/json');
      console.log(`Se Accept-Language value into ${locale}`)
      customHeaders.set('Accept-Language', locale);

      if (authStore.isLoggedIn) {
        customHeaders.set('Authorization', `Bearer ${authStore.accessToken}`);
      }
      options.headers = customHeaders;
    },
    onResponseError({ response }) { }
  });

  // nuxtApp.hook('i18n:beforeLocaleSwitch', ({ newLocale }) => {
  //   $customFetch(newLocale);
  // })

  nuxtApp.hook('i18n:localeSwitched', ({oldLocale, newLocale}) => {
    $customFetch(newLocale);
  })

  return {
    provide: {
      customFetch: $customFetch
    }
  }
});
