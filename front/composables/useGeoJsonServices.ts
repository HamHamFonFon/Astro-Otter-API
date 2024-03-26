interface Properties {
  name?: string | null,
  type?: string | null,
  mag?: number | null
}
interface Feature {
  type: string,
  geometry: Geometry,
  id: string
  properties: Properties
}

interface FeatureCollection {
  type?: string,
  features?: Feature[]
}

const geoJsonConstellation = (constellation: Constellation): FeatureCollection => {
  const feature: Feature = {
    id: constellation.id,
    type: "Feature",
    geometry: constellation.geometryLine,
    properties: {
      name: constellation.alt
    }
  }

  return {
    'type': 'FeatureCollection',
    'features': [
      feature
    ]
  }
}

const geoJsonDso = (dsos: Dso[]): FeatureCollection => {
  const features: Feature[] = dsos.map((dso: Dso): Feature => {
    return {
      type: 'feature',
      id: dso.id,
      geometry: dso.geometry,
      properties: {
        name: dso?.fullNameAlt,
        type: dso?.type,
        mag: dso?.magnitude
      }
    }
  });

  return {
    'type': 'FeatureCollection',
    'features': features
  }
}

export const useGeoJsonServices = () => {
  return {
    geoJsonConstellation,
    geoJsonDso
  }
}
