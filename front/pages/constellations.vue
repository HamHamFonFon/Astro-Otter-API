<script setup lang="ts">
import {definePageMeta, useI18n} from '#imports';
const { t } = useI18n();
import backgroundConstellationImage from '@/assets/images/background/constellations.jpg';

definePageMeta({
  layout: 'page'
});

applySeo({
  title: t('constellations.title'),
  description: t('constellations.description'),
  image: backgroundConstellationImage,
  fullUrl: ''
});


const TitleImageHero = defineAsyncComponent(() => import('@/components/Content/TitleImageHero.vue'));
const backgroundImage = ref(backgroundConstellationImage);
</script>

<template>
  <TitleImageHero
    :title="t('constellations.title')"
    :url-image="backgroundImage"
  />
</template>

<style scoped>

</style>
