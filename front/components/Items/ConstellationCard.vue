<template>
  <v-hover v-slot:default="{ isHovering, props }">
    <v-card
      class="ma-3"
      color="primary"
      style="overflow: hidden"
      :elevation="isHovering ? 18 : 1"
      v-bind="props"
  >
    <router-link :to="{name: 'constellation', params: { constellationId: item.id.toLowerCase(), urlName: item.urlName } }">
      <v-img
          :src="cover"
          :lazy-src="cover"
          class="bg-grey-lighten-2"
          height="300"
          cover
          :alt="item.alt.toLowerCase()"
          :aria-label="item.alt.toLowerCase()"
      >
        <template v-slot:placeholder>
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
          <div class="d-flex text-white v-card--reveal display-3 white--text" style="height: 100%;">
            <v-card-title class="text-center text-h5 text-white">
              <p>{{ item.alt }}</p>
              <p class="text-caption"> {{ item.generic }}</p>
            </v-card-title>
          </div>
        </v-expand-transition>
      </v-img>
    </router-link>
  </v-card>
  </v-hover>
</template>

<script setup>
import { onMounted, ref} from "vue";
const cover = ref(null);

const props = defineProps({
  item: {
    type: Object,
    default: null
  }
});

onMounted(() => {
  try {
    getCoverUrl();
  } catch (error) {
    console.error(`Error loading cover file ${props.item.cover}`, error);
  }
});

const getCoverUrl = async () => {
  cover.value = (await import(`@/assets/images/constellations/cover/${props.item.cover}`)).default;
}

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
