declare global {
  interface Geometry {
    type:        string;
    coordinates: number[];
  }

  interface AstrobinUser {
    id:          number;
    username:    string;
    about:       string;
    avatar:      string;
    image_count: number;
    job:         string;
    hobbies:     string;
    language:    string;
    website:     string;
  }
  interface Astrobin {
    uploaded: Date;
    title: string;
    subjects: string[];
    description?: string;
    url_gallery: string;
    url_thumb: string;
    url_regular: string;
    url_hd: string;
    url_solution: string;
    url_histogram: string;
    url_skyplot: string;
    user: string;
    views: number;
    likes: number;
    url_advanced_solution?: string;
  }

  interface Constellation {
    id: string,
    elasticSearchId: string,
    locale: string,
    updatedAt: Date | null,
    alt: string,
    urlName: string | null,
    description: string | null,
    geometry: Geometry,
    geometryLine: Geometry,
    kind: string,
    generic: string | null,
    cover: string | Object | undefined,
    context: string
  }

  interface Dso {
    id: string,
    elasticSearchId: string,
    locale: string,
    updatedAt: Date | null,
    name: string,
    urlName?: string,
    fullNameAlt: string | null,
    catalogs: string[] | null,
    catalogsLabel: string[] | null,
    desigs:            string[];
    otherDesigs:       { [key: string]: string };
    alt: string | null,
    descripttion: string | null,
    type: string,
    typeLabel: string,
    magnitude: number | null,
    discover: string | null,
    discoverYear: string | null,
    geometry: Geometry,
    declinaison: string | null,
    rightAscencion: string | null,
    distanceLightYear: number | null,
    distanceParsec : number | null,
    constId : string | null,
    constellation: Constellation,
    astrobinId: string | null,
    astrobin: Astrobin | null,
    astrobinUser: AstrobinUser | null,
    context: string
  }

  interface SearchItem {
    id: string,
    urlName?: string,
    context: string
  }
  interface SearchConstellationItem extends SearchItem {
    alt: string,
    generic?: string,
    cover?: string,
  }

  interface SearchDsoItem extends SearchItem {
    fullNameAlt: string,
    desigs: string[],
    otherDesigs: string[],
    typeLabel: string,
    constellation?: SearchConstellationItem,
  }

  interface ValueFilter {
    name: string,
    count: number,
    label: string
  }
  interface Filters {
    constellation: {[type: string ]: ValueFilter[]},
    catalog: {[type: string ]: ValueFilter[]},
    magnitude: {[type: string ]: ValueFilter[]},
    type: {[type: string ]: ValueFilter[]}
  }

  interface BrowserResponse {
    items?: Dso[],
    filters: {[type: string ]: Filters[] },
    total?: number
  }
}

export { Constellation, Dso, SearchConstellationItem, SearchDsoItem, BrowserResponse, Filters }
