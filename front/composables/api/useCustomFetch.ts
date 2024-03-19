import type { UseFetchOptions } from 'nuxt/app';

export function useCustomFetch<T>(
  url: string | (() => string),
  options: UseFetchOptions<T> = {}
) {
  const { locale  } = useI18n();
  return useFetch(url, {
    ...options,
    $fetch: useNuxtApp().$customFetch(locale.value),
  })
}
