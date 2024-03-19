interface dsoList {
  id: string,
  urlName: string | null,
  text: string,
  type: string,
  cover: string | null
}

interface constellationList {
  id: string,
  urlName: string | null,
  text: string,
  cover: string | null
}

interface SearchResponse {
  dsos: dsoList[] | null,
  constellations: constellationList[] | null,
  nbItems: number | 0
}

export const useFormatDataResponse = (items: unknown): SearchResponse | null =>{

    // let nbDso: number = 0;
    // let nbConstellations: number = 0;
    // let dsosList = null;
    // let constellationsList = null;

    // dsosList = items.filter(item => 'App\\Model\\Dso' === item.context).map(item => {
    //   const otherDesigs: string = (0 < Object.keys(item.otherDesigs).length) ? ' ('+ Object.values(item.otherDesigs).join(', ')+')' : ''
    //   return {
    //     id: item.id,
    //     urlName: item.urlName ?? null,
    //     text: item.fullNameAlt + otherDesigs,
    //     type: item.typeLabel,
    //     cover: null
    //   }
    // });
    // nbDso = dsosList.length;
    //
    // constellationsList = items.filter(item => 'App\\Model\\Constellation' === item.context).map(item => {
    //   return {
    //     id: item.id.toLowerCase(),
    //     urlName: item.urlName ?? null,
    //     text: item.alt,
    //     cover: item.cover
    //   }
    // });
    // nbConstellations = constellationsList.length

    return null; /*return {
        dsos: dsosList,
        constellations: constellationsList,
        nbItems: nbDso + nbConstellations
      }*/
}
