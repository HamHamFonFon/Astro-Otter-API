<script setup lang="ts">
import {definePageMeta} from "#imports";

const route = useRoute()
const { id, urlName } = route.params;

definePageMeta({
  layout: 'page'
})

</script>

<template>
  <div>{{ id }} - {{ urlName }}</div>
</template>

<style scoped>

</style>
