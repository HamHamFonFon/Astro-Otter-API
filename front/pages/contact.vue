<script setup lang="ts">
import {definePageMeta, useI18n} from '#imports';
const { t } = useI18n();
const TitlePage = defineAsyncComponent(() => import('@/components/Content/TitlePage.vue'));

definePageMeta({
  layout: 'page'
});
// const submittedFormData = ref(null);
</script>

<template>
  <v-sheet
    elevation="0"
    class="landing-warpper"
    color="transparent"
  >
    <TitlePage :title="t('contact.title')" />

    <v-container class="text-left">
      <v-sheet
        elevation="0"
        class="mx-auto landing-warpper"
        rounded
        color="background"
      >
        <v-sheet
          class="pa-3"
          elevation="0"
          color="transparent"
        >
          <v-container>
            Insert form here
          </v-container>
        </v-sheet>
      </v-sheet>
    </v-container>
  </v-sheet>
</template>

<style scoped>

</style>
