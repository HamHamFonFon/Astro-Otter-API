<template>
  <v-sheet
    elevation="0"
    class="mx-auto"
    rounded
    :style="backgroundStyle"
  >
    <v-container class="text-center" :style="{margin: 'auto'}">
      <v-sheet color="transparent" elevation="0">
        <img class="mx-auto" width="200" height="200" src="@/assets/images/logos/astro_otter_200-200.png" :alt="$t('layout.logo')" style="border-radius: 50%" />
        <v-card
          color="transparent"
          elevation="0"
          max-width="800"
          class="mx-auto my-10 py-0py-md-12"
        >

          <h1 class="text-h2 mt-4 mx-auto text-shades-white">
            {{ $t('home.explore') }}
          </h1>
        </v-card>
        <v-row class="w-auto">
          <v-col cols="12" sm="6">
            <v-btn
              block
              size="x-large"
              class="text-white mr-5 font-weight-bold"
              color="primary"
              @click="scrollToSearch"
              :aria-label="$t('home.searchArialabel')"
            >{{ $t('home.search') }}</v-btn
            >
          </v-col>
          <v-col cols="12" sm="6">

            <v-btn
              block
              size="x-large"
              class="text-grey mr-5 font-weight-bold"
              color="grey"
              variant="outlined"
              :aria-label="$t('home.browseArialabel')"
            ><router-link :to="{name: 'catalogs'}">{{ $t('home.browse') }}</router-link>
            </v-btn>
          </v-col>
        </v-row>
      </v-sheet>
    </v-container>
  </v-sheet>
</template>

<script setup>
import backgroundImage from '@/assets/images/background/bg-1.webp'
import {computed} from "vue";

const backgroundStyle = computed(() => ({
  backgroundImage: `url(${backgroundImage})`, backgroundSize: 'cover', height: '100vh', display: 'flex'
}));

/**
 *
 */
const scrollToSearch = () => {
  const homeSearch = document.getElementById('search');
  if (homeSearch) {
    homeSearch.scrollIntoView({ behavior: "smooth" })
  }
}
</script>

<style scoped>

</style>
