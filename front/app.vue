<template>
  <Notifications />
  <v-app>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </v-app>
</template>

<script setup lang="ts">
import {defineAsyncComponent} from "vue";
const Notifications = defineAsyncComponent(() => import('@/components/Layout/Notifications.vue'));
</script>
