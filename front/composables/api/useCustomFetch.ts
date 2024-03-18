import type { UseFetchOptions } from 'nuxt/app';

export function useCustomFetch<T>(
  url: string | (() => string),
  options: UseFetchOptions<T> = {}
) {
  const { locale  } = useI18n();
  console.log(`Locale in customFetch : ${locale}` )

  return useFetch(url, {
    ...options,
    $fetch: useNuxtApp().$customFetch('en'),
  })
}
