<script setup lang="ts">
import {definePageMeta, useI18n} from '#imports';
const DsoBrowser = defineAsyncComponent(() => import('@/components/Content/DsoBrowser.vue'));
const { t } = useI18n();

definePageMeta({
  layout: 'page'
});

const TitlePage = defineAsyncComponent(() => import('@/components/Content/TitlePage.vue'));
</script>

<template>
  <TitlePage :title="t('catalogs.title')" />

  <dso-browser />
</template>

<style scoped>

</style>
