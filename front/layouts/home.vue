<template>
  <!-- Body -->
  <v-main class="main-container">
    <div class="flex-fill">
      <slot />
      <HeaderBarHome />
      <SocialSharing />
      <BackToTop />
    </div>
  </v-main>

  <!-- Footer -->
  <FooterBar />
</template>

<script setup lang="ts">
import { defineAsyncComponent} from "vue";

const HeaderBarHome = defineAsyncComponent(() => import('@/components/Layout/HeaderBarHome.vue'))
const SocialSharing = defineAsyncComponent(() => import('@/components/Layout/SocialSharing.vue'))
const BackToTop = defineAsyncComponent(() => import('@/components/Layout/BackToTop.vue'));

const FooterBar = defineAsyncComponent(() => import('@/components/Layout/FooterBar.vue'));
</script>
