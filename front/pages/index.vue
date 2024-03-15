<script setup lang="ts">
import { useI18n } from '#imports';
import type {Component} from "vue";
const { t } = useI18n();

definePageMeta({
  layout: 'home',
});

applySeo({
  title: t('home.explore'),
  description: t('home.description'),
  image: "@/assets/images/logos/astro_otter_200-200.png",
});

const HeroPresentation = defineAsyncComponent(() => import('@/components/Home/HeroPresentation.vue'))
const SearchAutocomplete = defineAsyncComponent(() => import("@/components/Home/SearchAutocomplete.vue"))
// const RandomDsoHomepage = defineAsyncComponent(() => import("@/components/Home/RandomDsoHomepage.vue"))

const { homepagesItems } = useHomepagesItems();
const ItemCard = defineAsyncComponent(() => import('@/components/Home/ItemCard.vue'));
const ItemCardDefault = defineAsyncComponent(() => import('@/components/Home/Items/default.vue'));
const ItemCardBackground = defineAsyncComponent(() => import('@/components/Home/Items/background.vue'));
const homeComponents: { default: Component, background: Component } = {
  'default': ItemCardDefault,
  'background': ItemCardBackground
};

const processedItems = computed(() => homepagesItems );
</script>

<template>
  <!-- Hero component -->
  <HeroPresentation />

  <!-- Search autocomplete component -->
  <a
    id="search"
    ref="search"
  />
  <SearchAutocomplete />

  <a
    id="pages"
    ref="pages"
  />
  <ItemCard :items="processedItems">
    <template v-slot="{ item }">
      <component
        :is="homeComponents[item.component]"
        :item="item"
      />
    </template>
  </ItemCard>


  <!-- Random DSO ? -->
<!--  <RandomDsoHomepage />-->
</template>

<style scoped>
</style>
