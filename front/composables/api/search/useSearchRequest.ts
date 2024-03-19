export const useSearchRequest = async (searchTerms: string): Promise<any> => {
  const endpoint: string = '/search';

  return useAsyncData(
    () => useCustomFetch<[]>(endpoint, {
        method: 'POST',
        body: {
          terms: searchTerms
        }
      }
    )
  );
}
