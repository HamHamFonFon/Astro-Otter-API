<template>
  <v-sheet
    elevation="1"
    class="mx-auto landing-warpper"
    rounded
    color="transparent"
  >
    <div id="celestial-map"></div>
    <v-container>
      <v-row>

      </v-row>

      <v-row align="center" justify="center">
        <v-btn
            @click="centerToConstellation"
            size="x-large"
            variant="outlined"
            class="text-white mr-5"
            color="grey"
        > Center
        </v-btn>
      </v-row>
    </v-container>
  </v-sheet>
</template>

<script setup>
import {onBeforeMount, onMounted, ref} from "vue";
import celestial from "d3-celestial";
import {dsoColors} from "@/configs/mapColors";
// import { buildMap } from "@/services/buildMap";

const starsFile = require('@/data/stars.8.json')

const props = defineProps({
  centerMap: {
    type: Object,
    default: null
  },
  constellationId: {
    type: String,
    default: ''
  },
  constellationGeoData: {
    type: Object,
    default: null
  },
  itemsGeoData: {
    type: Object,
    default: null
  }
});

let Celestial = celestial.Celestial();
const zoom = ref(5);
const starsConstellation = ref(null);

onBeforeMount(() => loadStars())
onMounted(() => {
  let backgroundConfig = {
    background: {
      fill: "#404040",   // Area fill
      opacity: 1,
      stroke: "#2B2A34", // Outline
      width: 1.5
    }
  };

  let horizonConfig = {
    horizon: {
      show: false,
      stroke: "#000099", // Line
      width: 1.0,
      fill: "#000000",   // Area below horizon
      opacity: 0.5
    }
  };

  let linesConfig = {
    lines: {
      graticule: {
        show: true, stroke: "#1B2A32", width: 0.6, opacity: 1,
        // grid values: "outline", "center", or [lat,...] specific position
        lon: {pos: [""], fill: "#1B2A32", font: "10px Helvetica, Arial, sans-serif"},
        // grid values: "outline", "center", or [lon,...] specific position
        lat: {pos: [""], fill: "#1B2A32", font: "10px Helvetica, Arial, sans-serif"}
      },
      equatorial: {show: false, stroke: "#aaaaaa", width: 1.3, opacity: 0.7},
    }
  }

  let mwConfig = {
    mw: {
      show: false
    }
  };

  let starsConfig = {
    stars: {
      show: false,
      data: 'no_stars.json',
      colors: true,
      names: false,
      proper: false,
      style: {fill: "#ffffff", opacity: 1},
      namelimit: 2,
      limit: 8,
      size: 5,
    }
  };

  let constellationsConfig = {
    constellations: {
      show: true,    // Show constellations
      names: true,   // Show constellation names
      desig: false,   // Show short constellation names (3 letter designations)
      namestyle: {
        fill: "#e9e9e9", align: "center", baseline: "middle",
        font: ["14px Helvetica, Arial, sans-serif",  // Style for constellations
          "12px Helvetica, Arial, sans-serif",  // Different fonts for diff.
          "11px Helvetica, Arial, sans-serif"]
      },// ranked constellations
      lines: true,   // Show constellation lines, style below
      linestyle: {stroke: "#A06060", width: 1, opacity: 0.6},
      bounds: true, // Show constellation boundaries, style below
      boundstyle: {stroke: "#346464", width: 0.5, opacity: 0.8, dash: [2, 4]}
    }
  };

  let dsosConfig = {
    dsos: {
      show: true,
      data: 'no_dso.json',
      names: true,
      limit: 1000,
      namelimit: 1000,
      symbols: dsoColors
    }
  };

  let config = {
    width: 0,
    projection: "equirectangular", // Map projection used: airy, aitoff, armadillo, august, azimuthalEqualArea, azimuthalEquidistant, baker, berghaus, boggs, bonne, bromley, collignon, craig, craster, cylindricalEqualArea, cylindricalStereographic, eckert1, eckert2, eckert3, eckert4, eckert5, eckert6, eisenlohr, equirectangular, fahey, foucaut, ginzburg4, ginzburg5, ginzburg6, ginzburg8, ginzburg9, gringorten, hammer, hatano, healpix, hill, homolosine, kavrayskiy7, lagrange, larrivee, laskowski, loximuthal, mercator, miller, mollweide, mtFlatPolarParabolic, mtFlatPolarQuartic, mtFlatPolarSinusoidal, naturalEarth, nellHammer, orthographic, patterson, polyconic, rectangularPolyconic, robinson, sinusoidal, stereographic, times, twoPointEquidistant, vanDerGrinten, vanDerGrinten2, vanDerGrinten3, vanDerGrinten4, wagner4, wagner6, wagner7, wiechel, winkel3
    transform: "equatorial", // Coordinate transformation: equatorial (default), ecliptic, galactic, supergalactic
    controls: false, // zoom controls

    // geopos: props.centerMap,
    //center: props.centerMap,
    adaptable: false,
    interactive: true,
    form: false,
    location: false,

    container: "celestial-map",
    // zoomlevel: zoom.value,
    datapath: "/data",

    planets: {
      show: false
    },
    ...mwConfig,
    ...starsConfig,
    ...constellationsConfig,
    ...dsosConfig,
    ...linesConfig,
    ...backgroundConfig,
    ...horizonConfig
  };

  if (props.centerMap) {
    config["center"] = props.centerMap;
  }

  const pointStyle = {fill: "#ffffff", opacity: 1, width: 3};
  const textStyle = {fill: "rgba(255, 0, 204, 1)", font: "normal bold 15px Helvetica, Arial, sans-serif", align: "left", baseline: "bottom"};

  // Add stars
  Celestial.add({
    type: 'raw',
    callback: (err, json) => {
      if (err) console.error(err.message, json);
      const stars = Celestial.getData(starsConstellation.value, config.transform);
      Celestial.container.selectAll('.stars').data(stars.features)
          .enter().append('path').attr('class', 'stars');
      Celestial.redraw()
    },
    redraw: () => {
      Celestial.container.selectAll('.stars').each((d) => {
        Celestial.setStyle(pointStyle);
        let pt = Celestial.mapProjection(d.geometry.coordinates),
            r = Math.pow(8 - d.properties.mag, 0.7); // starSize(d, config);
        Celestial.context.fillStyle = Celestial.starColor(d);
        Celestial.context.beginPath();
        Celestial.context.arc(pt[0], pt[1], r, 0, 2 * Math.PI);
        Celestial.context.closePath();

        // Project objects on map
        Celestial.map(d);
        // draw on canvas
        Celestial.context.fill();
        Celestial.context.stroke();
        Celestial.context.fillText(d.properties.name, pt[0], pt[1]);
      })
    }
  });

  // Add dos
  if (null !== props.itemsGeoData) {
    Celestial.add({
      type: 'raw',
      callback: (err, json) => {
        if (err) console.error(err.message, json);
        const dsos = Celestial.getData(props.itemsGeoData, config.transform);
        Celestial.container.selectAll('.dsos').data(dsos.features)
            .enter().append('path').attr('class', 'stars');
        Celestial.redraw()
      },
      redraw: () => {
        //var m = c.metrics(),
        // let quadtree = d3.geom.quadtree().extent([[-1, -1], [m.width + 1, m. height + 1]])([]);

        Celestial.container.selectAll(".dsos").each(function (d) {
          if (Celestial.clip(d.geometry.coordinates)) {
            const pt = Celestial.mapProjection(d.geometry.coordinates);
            const r = Math.pow(20 - d.properties.magnitude, 0.7);
            Celestial.setStyle(pointStyle);
            Celestial.context.beginPath();
            Celestial.context.arc(pt[0], pt[1], r, 0, 2 * Math.PI);
            Celestial.context.closePath();
            Celestial.context.stroke();
            Celestial.context.fill();

            // Find nearest neighbor
            // const nearest = d3.quadtree.find(pt);
            // If neigbor exists, check distance limit
            // if (!nearest || distance(nearest, pt) > 20) {
              // Nothing too close, add it and go on
              // d3.quadtree.add(pt)
              Celestial.setTextStyle(textStyle);
              Celestial.context.fillText(d.properties.name, pt[0] + r + 2, pt[1] + r + 2);
            // }
          }
        });
      }
    })
  }

  Celestial.display(config);
  setTimeout(() => centerToConstellation(), 1000);
});

const loadStars = () => {
  const features = starsFile.features;
  const filteredFeatures = features.filter(feature => props.constellationId === feature.properties.con && 8 > feature.properties.mag);
  starsConstellation.value = {
    "type": "FeatureCollection",
    "features": filteredFeatures
  };
};

// const distance = (p1, p2) => {
//   const
//       d1 = p2[0] - p1[0],
//       d2 = p2[1] - p1[1];
//   return Math.sqrt(d1 * d1 + d2 * d2);
// }
const centerToConstellation = () =>  {
  Celestial.zoomBy(zoom.value);
  if (props.centerMap) {
    Celestial.position()
  } else {
    Celestial.showConstellation(props.constellationId);
  }

}
</script>
