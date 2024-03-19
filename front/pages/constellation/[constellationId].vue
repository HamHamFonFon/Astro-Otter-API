<script setup lang="ts">
const route = useRoute()
const { id, urlName } = route.params;
</script>

<template>
  <div>{{ id }} - {{ urlName }}</div>
</template>

<style scoped>

</style>
