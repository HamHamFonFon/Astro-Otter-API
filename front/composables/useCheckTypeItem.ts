const CONTEXT_DSO: string = 'App\\Model\\Dso';
const CONTEXT_CONST: string = 'App\\Model\\Constellation';
const isDso = (item: Dso | SearchItem): boolean => {
  return CONTEXT_DSO === item.context
}
const isConstellation = (item: Constellation | SearchItem): boolean => {
  return CONTEXT_CONST === item.context;
}

export const useCheckTypeItem = () => {
  return {
    isDso,
    isConstellation
  }
}
