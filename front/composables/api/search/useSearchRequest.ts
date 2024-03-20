export const useSearchRequest = async (searchTerms: string): Promise<any> => {
  const endpoint: string = '/search';

  const {data, pending, error, status} = useAsyncData(

    () => useCustomFetch<[SearchDsoItem|SearchConstellationItem]>(endpoint, {
        method: 'POST',
        body: {
          terms: searchTerms
        }
      }
    )
  );

  console.log(status.value);
  console.log(data.value);

  return {data, pending, error, status};
}
