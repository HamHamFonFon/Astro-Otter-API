<script setup lang="ts">
import { useI18n } from '#imports';
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
const homeComponents: { default: Component | undefined, background: Component | undefined } = {
  'background': ItemCardBackground,
  'default': ItemCardDefault
};
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
  <ItemCard
    :components="homeComponents"
  >
    <template #default="{ component, index }">
      <component
        :is="component"
        :item="homepagesItems.filter(item => index === item.component)[0]"
        :index="index"
      />
    </template>
  </ItemCard>


  <!-- Random DSO ? -->
<!--  <RandomDsoHomepage />-->
</template>

<style scoped>
</style>
