import type { UseFetchOptions } from 'nuxt/app';

export function useCustomFetch<T>(
  endpoint: string | (() => string),
  options: UseFetchOptions<T> = {}
) {

  const { locale } = useI18n();
  const headerLanguage = {
    'Accept-Language': locale
  }

  options.headers = {...headerLanguage};

  return useFetch(endpoint, {
    ...options,
    $fetch: useNuxtApp().$customFetch,
  })
}
