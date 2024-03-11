<template>
  <v-container class="text-center" :key="index">
    <v-row>
      <v-col cols="12" md="6">
        <v-sheet elevation="0">
          <v-card
            elevation="0"
            max-width="800"
            class="mx-auto my-10"
          >
            <h1
              style="color: #4a4d6d"
              class="font-weight-black text-h3 text-lg-h2 text-xl-h1"
            >
              {{ item.text }}
            </h1>
            <h2 class="text-h6 text-green mt-10 mx-auto">
              {{ item.description }}
            </h2>
          </v-card>
          <div class="text-center">
            <router-link :to="item.path" v-bind:title="$t('layout.btnExplore')">
              <v-btn size="x-large" class="text-white font-weight-bold" color="primary" :aria-label="$t('layout.btnExplore')">{{ $t('layout.btnExplore') }}</v-btn>
            </router-link>
          </div>
        </v-sheet>
      </v-col>
      <v-col cols="0" md="6">
        <v-card>
          <router-link :to="item.path" v-bind:title="$t('layout.btnExplore')">
            <v-img
              height="420"
              class="v-card--hover"
              cover
              :src="item.image"
            ></v-img>
          </router-link>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import {toRefs} from "vue";

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
const { item, index } = toRefs(props)
</script>

<style scoped>

</style>
