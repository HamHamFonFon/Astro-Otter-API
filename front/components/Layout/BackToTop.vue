<template>
  <v-btn class="backToTop" :class="{ visible: isVisible }" @click="scrollToTop" :color="backgroundColor">
    <v-icon
      small
      color="green"
      icon="mdi-arrow-up"
    ></v-icon>
  </v-btn>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";

const isVisible = ref(false);

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});
onUnmounted(() => {
  window.removeEventListener("scroll", handleScroll);
});

const handleScroll = () => {
  isVisible.value = window.scrollY > 200;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};
const backgroundColor = computed(() => (screen.width <= 760) ? 'primary': 'transparent')
</script>

<style  lang="scss">
@import "@/assets/scss/components/backToTop.scss";
</style>