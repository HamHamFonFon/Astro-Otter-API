<template>
  <!-- Add bar -->
  <HeaderBar :allRoutes="allRoutes" />

  <!-- Breadcrumbs -->
<!--  <BreadCrumb />-->

  <!-- -->
  <v-main class="main-container main-background">
    <div class="flex-fill">
      <slot></slot>
      <SocialSharing />
      <BackToTop />
    </div>
  </v-main>

  <!-- Footer -->
  <FooterBar :allRoutes="allRoutes" />
</template>

<script setup>

import {computed, defineAsyncComponent} from "vue";
import {useRoute, useRouter} from "vue-router";
const route = useRoute();

import {applySeo} from "@/services/seo";
import astroOtterLogo from '@/assets/images/logos/astro_otter_200-200.png'

applySeo({
  title: route.meta.title,
  description: route.meta.description,
  image: astroOtterLogo,
  imageAlt: route.meta.title,
  fullUrl: route.fullPath
});

const HeaderBar = defineAsyncComponent(() => import('@/components/Layout/HeaderBar.vue'));
const FooterBar = defineAsyncComponent(() => import('@/components/Layout/FooterBar.vue'));

const SocialSharing = defineAsyncComponent(() => import('@/components/Layout/SocialSharing.vue'))
const BackToTop = defineAsyncComponent(() => import('@/components/Layout/BackToTop.vue'));

const allRoutes = computed(() => useRouter().options.routes)
// import BreadCrumb from "@/components/Layout/BreadCrumb.vue";
</script>
