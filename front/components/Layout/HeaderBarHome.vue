<script setup lang="ts">
import { defineAsyncComponent } from "vue";
const LanguageSwitcher = defineAsyncComponent(() => import('@/components/Layout/LanguageSwitcher.vue'))
</script>

<template>
  <div class="headerHome d-flex">
    <v-spacer />
    <LanguageSwitcher
      btn-color="transparent"
      icon-color="#1ed760"
      bg-color="background"
    />
  </div>
</template>

<style scoped>
.headerHome {
  top: 0.5em;
  position: fixed;
  z-index: 999;
  transition: all 0.3s;
  right: 5px;
}
</style>
