<script setup lang="ts">

import {definePageMeta} from "nuxt/dist/pages/runtime";

const HeroPresentation = defineAsyncComponent(() => import('@/components/Home/HeroPresentation.vue'))
// const SearchAutocomplete = defineAsyncComponent(() => import("@/components/Home/SearchAutocomplete.vue"))
// const RandomDsoHomepage = defineAsyncComponent(() => import("@/components/Home/RandomDsoHomepage.vue"))

// const ItemCard = defineAsyncComponent(() => import('@/components/Home/ItemCard.vue'));
// const ItemCardDefault = defineAsyncComponent(() => import('@/components/Home/Items/default.vue'));
// const ItemCardBackground = defineAsyncComponent(() => import('@/components/Home/Items/background.vue'));
// const homeComponents = {
//   'default': ItemCardDefault,
//   'background': ItemCardBackground
// };

definePageMeta({
  layout: 'Home'
});
</script>

<template>
  <!-- Hero component -->
  <HeroPresentation />

  <!-- Search autocomplete component -->
<!--  <a-->
<!--    id="search"-->
<!--    ref="search"-->
<!--  />-->
<!--  <SearchAutocomplete />-->

<!--  <a-->
<!--    id="pages"-->
<!--    ref="pages"-->
<!--  />-->
<!--  <ItemCard :items="processedItems">-->
<!--    <template #default="{item, index}">-->
<!--      <component-->
<!--        :is="homeComponents[item.component]"-->
<!--        :item="item"-->
<!--        :index="index"-->
<!--      />-->
<!--    </template>-->
<!--  </ItemCard>-->

  <!-- Random DSO ? -->
<!--  <RandomDsoHomepage />-->
</template>

<style scoped>

</style>
