<script setup lang="ts">
import { useI18n } from '#imports';
const { t } = useI18n();

import backgroundConstellationImage from '@/assets/images/background/constellations.jpg';

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
