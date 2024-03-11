<template>
  <v-sheet
    :key="index"
    color="transparent"
    class="mx-auto landing-warpper"
    elevation="0"
    :style="{
      'background-image': `url(${item.image})`,
      'background-size': 'cover',
      'background-position': 'top',
      'margin': 0
    }"
  >
    <v-row no-gutters>
      <v-col cols="12" md="12">
        <v-card
          elevation="0"
          class="pa-10 d-flex flex-column align-center justify-center"
          height="600"
          color="transparent"
        >
          <h1
            class="font-weight-black text-h3 text-lg-h2 text-xl-h1"
          >
            {{ item.text }}
          </h1>
          <p class="text-h6 text-green mt-10 mx-auto">
            {{ item.description }}
          </p>

          <div class="text-center">
            <router-link :to="item.path" v-bind:title="$t('layout.btnExplore')">
              <v-btn size="x-large" class="text-white font-weight-bold mt-10" color="primary" :aria-label="$t('layout.btnExplore')">{{ $t('layout.btnExplore') }}</v-btn>
            </router-link>
          </div>

        </v-card>
      </v-col>
    </v-row>
  </v-sheet>
</template>

<script setup>
import { toRefs} from "vue";

const props = defineProps({
  item: {
    type: Object,
    default: null
  },
  index: {
    type: String,
    default: null
  }
})
const { item, index } = toRefs(props);

</script>

<style scoped>

</style>
