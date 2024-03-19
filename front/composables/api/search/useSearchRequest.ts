export const useSearchRequest = async (searchTerms: string): Promise<any> => {
  const {
    data: items,
    error,
    pending,
    refresh
  } = await useAsyncData(
    'items',
    () => useCustomFetch<[]>('/search', {
        method: 'POST',
        body: {
          terms: searchTerms
        }
      }
    ),
    {
      watch: [searchTerms]
    }
  );

  return items;
}
