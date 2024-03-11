<template>
  <v-sheet
    elevation="1"
    class="mx-auto landing-warpper"
    rounded
  >
    <slot
      v-for="item in items"
      :item="item"
    ></slot>
  </v-sheet>
</template>

<script setup>
defineProps({
  items: {
    type: Object,
    default: null,
  }
})
</script>

<style scoped>

</style>