import countries from 'i18n-iso-countries';
import langs from 'i18n-iso-countries/langs/en.json';

export const useListCountries = () => {
  countries.registerLocale(langs);
  const list = countries.getNames('en', { select: 'official' })

  return Object.keys(list).map(k => ({key: k.toUpperCase(), value: list[k]}));
}
