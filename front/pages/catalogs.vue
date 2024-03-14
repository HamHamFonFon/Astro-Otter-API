<script setup lang="ts">
import {definePageMeta, useI18n} from '#imports';
const { t } = useI18n();

definePageMeta({
  layout: 'page'
});

const TitlePage = defineAsyncComponent(() => import('@/components/Content/TitlePage.vue'));
</script>

<template>
  <TitlePage :title="t('catalogs.title')" />
</template>

<style scoped>

</style>
