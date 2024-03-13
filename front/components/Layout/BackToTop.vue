<template>
  <v-btn
    class="backToTop"
    :class="{ visible: isVisible }"
    :color="backgroundColor"
    @click="scrollToTop"
  >
    <v-icon
      small
      color="green"
      icon="mdi-arrow-up"
    />
  </v-btn>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";
const { isMobile } = useDevice();
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
const backgroundColor = computed(() => (isMobile) ? 'primary': 'transparent')
</script>

<style  lang="scss">
@import "@/assets/scss/components/backToTop.scss";
</style>
