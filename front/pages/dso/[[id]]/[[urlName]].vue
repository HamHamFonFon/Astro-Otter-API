<script setup lang="ts">
import {definePageMeta} from "#imports";

definePageMeta({
  layout: 'page'
})

const route = useRoute()
const { id, urlName } = route.params;
</script>

<template>
  <div>{{ id }} - {{ urlName }}</div>
</template>

<style scoped>

</style>
