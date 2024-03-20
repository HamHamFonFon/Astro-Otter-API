<template>
  <v-hover v-slot="{ isHovering, props }">
    <v-card
      class="ma-5"
      color="primary"
      style="overflow: hidden"
      :elevation="isHovering ? 18 : 1"
      v-bind="props"
    >
      <NuxtLink
        :to="{ name: 'dso-id', params: { id: dso.id, urlName: dso.urlName } }"
        :title="t('dso.link', {'dso': title})"
      >
        <v-img
          :src="imageCover"
          :lazy-src="imageLazyCover"
          :class="isHovering ? 'zoom bg-grey-lighten-2': 'bg-grey-lighten-2'"
          height="340"
          cover
          :alt="t('dso.image', {'dso': title })"
          :aria-label="t('dso.image', {'dso': title })"
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
              ></v-progress-circular>
            </v-row>
          </template>
          <v-expand-transition>
            <div
              :class="getCardsCssClass(isDefaultImage, isHovering as boolean)"
              style="height: 100%;"
            >
              <v-card-title
                v-show="true === isHovering || true === isDefaultImage || true === isMobile"
                class="text-center text-h5 text-white"
              >
                <p>{{ title }}</p>
                <p
                  v-if="1 < dso.desigs.length"
                  class="text-caption"
                >
                  {{ otherDesigs }}
                </p>
              </v-card-title>
            </div>
          </v-expand-transition>
        </v-img>
      </NuxtLink>

      <v-card-actions color="background">
        <v-container :style="{margin: 'auto'}">
          <v-row class="w-auto">
            <v-col
              cols="12"
              sm="6"
            >
              <v-btn
                block
                elevation="1"
              >
                <span class="subheading me-2">{{ dso.typeLabel }}</span>
              </v-btn>
            </v-col>
            <v-col
              cols="12"
              sm="6"
            >
              <v-btn
                block
                :aria-label="t('layout.btnConstellationTo', {'constellation': dso.constellation.alt })"
                elevation="1"
              >
                <!-- v-icon class="mr-2" color="grey"><slot name="custom-icon" iconName="constellation"></slot> </v-icon-->
                <span class="subheading me-2">
                  <NuxtLink
                    :to="{
                      name: 'constellation-constellationId',
                      params: {
                        constellationId: dso.constellation.id.toLowerCase(),
                        urlName: dso.constellation.alt.toLowerCase()
                      }
                    }"
                    :title="t('layout.btnConstellationTo', {'constellation': dso.constellation.alt })"
                  >
                    {{ dso.constellation.alt }}
                  </NuxtLink>
                </span>
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-actions>
    </v-card>
  </v-hover>
</template>

<script setup lang="ts">
import {computed, toRefs} from "vue";

import defaultImage from '@/assets/images/default.png';
const { t } = useI18n();

const props = defineProps<{
  dso: Dso
}>()

const { dso } = toRefs(props);
const { isMobile } = useDevice();

const imageCover = computed(() => (dso.value.astrobinUser) ? dso.value.astrobin?.url_regular: defaultImage );
const isDefaultImage = computed(() => (!dso.value.astrobinUser) );
const imageLazyCover = computed(() => (dso.value.astrobinUser) ? dso.value.astrobin?.url_gallery: defaultImage );
const title = computed(() => dso.value.fullNameAlt );
const otherDesigs = computed(() => dso.value.desigs.filter((v: string) => v !== dso.value.name).join(' - '));

const getCardsCssClass = (isDefaultImage: boolean, isHovering: boolean) => {
  if (isMobile) {
    return 'd-flex text-white v-card--reveal display-3 white--text';
  }

  if (isDefaultImage) {
      return 'd-flex text-white v-card--reveal display-3 white--text'
  } else {
    if (isHovering && !isDefaultImage) {
      return 'd-flex transition-fast-in-fast-out text-white v-card--reveal display-3 white--text'
    }
  }
}

</script>

<style scoped lang="scss">
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: .5;
  position: absolute;
  width: 100%;
}

.v-card-title, .v-card-subtitle  {
  background-color: rgba(0, 0, 0, 0.7);
}

.v-img {
  border-bottom: solid #1ed760;
  .v-img__img { transition: all 0.2s; }
  &.zoom {
    .v-img__img { transform: scale(1.2); }
  }
}

</style>
