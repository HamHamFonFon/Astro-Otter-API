<template>
  <!-- Add bar -->
<!--  <HeaderBar :allRoutes="allRoutes" />-->

  <!-- -->
  <v-main class="main-container main-background">
    <div class="flex-fill">
      <slot />
      <SocialSharing />
      <BackToTop />
    </div>
  </v-main>

  <!-- Footer -->
  <FooterBar />
</template>

<script setup>

import { defineAsyncComponent} from "vue";

const HeaderBar = defineAsyncComponent(() => import('@/components/Layout/HeaderBar.vue'));
const FooterBar = defineAsyncComponent(() => import('@/components/Layout/FooterBar.vue'));

const SocialSharing = defineAsyncComponent(() => import('@/components/Layout/SocialSharing.vue'))
const BackToTop = defineAsyncComponent(() => import('@/components/Layout/BackToTop.vue'));
</script>
