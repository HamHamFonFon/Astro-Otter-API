<template>
  <!-- Body -->
  <v-main class="main-container">
    <div class="flex-fill">
      <slot />
<!--      <HeaderBarHome />-->
      <SocialSharing />
      <BackToTop />
    </div>
  </v-main>

  <!-- Footer -->
<!--  <FooterBar :allRoutes="allRoutes" />-->
</template>

<script setup>
import { defineAsyncComponent} from "vue";
import {useRoute} from "vue-router";
const route = useRoute();

import astroOtterLogo from '@/assets/images/logos/astro_otter_200-200.png'

// const HeaderBarHome = defineAsyncComponent(() => import('@/components/Layout/HeaderBarHome.vue'))
const SocialSharing = defineAsyncComponent(() => import('@/components/Layout/SocialSharing.vue'))
const BackToTop = defineAsyncComponent(() => import('@/components/Layout/BackToTop.vue'));

// const FooterBar = defineAsyncComponent(() => import('@/components/Layout/FooterBar.vue'));
// const allRoutes = computed(() => useRouter().options.routes)
</script>
