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
  <v-sheet
    elevation="0"
    class="landing-warpper"
    color="transparent"
  >
    <v-container class="text-left">
      <DsoBrowser />
    </v-container>
  </v-sheet>
</template>

<style scoped>

</style>
