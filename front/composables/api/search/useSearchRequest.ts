export const useSearchRequest = async (searchTerms: Ref<string>): Promise<any> => {
  const endpoint: string = '/search';
  return useCustomFetch<[SearchDsoItem | SearchConstellationItem]>(endpoint, {
      method: 'POST',
      body: {
        terms: searchTerms
      },
      watch: [searchTerms]
    }
  );
}
