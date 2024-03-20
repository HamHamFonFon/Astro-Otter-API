<template>
  <v-hover v-slot="{ isHovering, props }">
    <v-card
      class="ma-3"
      color="primary"
      style="overflow: hidden"
      :elevation="isHovering ? 18 : 1"
      v-bind="props"
    >
      <NuxtLink
        :to="{name: 'constellation-constellationId', params: { constellationId: constellation.id.toLowerCase(), urlName: constellation.urlName } }"
      >
        <v-img
          :src="`../assets/images/constellations/cover/${constellation.cover}`"
          class="bg-grey-lighten-2"
          height="300"
          cover
          :alt="constellation.alt.toLowerCase()"
          :aria-label="constellation.alt.toLowerCase()"
        >
          <template #placeholder>
            <v-row
              class="fill-height ma-0"
              align="center"
              justify="center"
            >
              <v-progress-circular
                indeterminate
                color="grey-lighten-5"
              />
            </v-row>
          </template>
          <v-expand-transition>
            <div
              class="d-flex text-white v-card--reveal display-3 white--text"
              style="height: 100%;"
            >
              <v-card-title class="text-center text-h5 text-white">
                <p>{{ constellation.alt }}</p>
                <p class="text-caption">
                  {{ constellation.generic }}
                </p>
              </v-card-title>
            </div>
          </v-expand-transition>
        </v-img>
      </NuxtLink>
    </v-card>
  </v-hover>
</template>

<script setup lang="ts">
import { toRefs } from "vue";
import type { VImg } from 'vuetify/components'
type srcObject = VImg["$props"]["src"];

const props = defineProps<{
  constellation: Constellation
}>()
const { constellation } = toRefs(props);

// TEST WITH computed()
const cover = computed<string | srcObject | undefined>(() => {
  try {
    return require(`~/assets/images/constellations/cover/${constellation.value.cover}`);
  } catch (error) {
    console.error(`Error loading cover file "${constellation.value.cover}"`, error);
  }
})

// TEST with function
// async function resolveImage() {
//   const img = await import(`~/assets/images/constellations/cover/${constellation.value.cover}`);
//   return img.default;
// }
// const cover = await resolveImage();

// TEST with new URL()
// function getCoverUrl() {
//   return new URL(`./assets/images/constellations/cover/${constellation.value.cover}`, import.meta.url).href
// }

// const lazyCover = computed<string | undefined>(() => cover.value as string)
</script>

<style scoped>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: .9;
  position: absolute;
  width: 100%;
}

.v-card-title {
  background-color: rgba(0, 0, 0, 0.5);
}
.v-img {
  border-bottom: solid #1ed760;
}

.v-img:hover {
  cursor: pointer;
}
</style>
