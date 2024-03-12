const buildApiHeaders = (customHeaders: any, queryParams: any, othersConfig: any) => {
  const headers: {Accept: string, 'Content-Type': string} = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }

  return {
    'headers': {
      ...headers,
      ...customHeaders
    },
    'params': queryParams,
    ...othersConfig
  }
}

export {
    buildApiHeaders
}
