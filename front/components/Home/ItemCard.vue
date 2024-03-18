<template>
  <v-sheet
    elevation="1"
    class="mx-auto landing-warpper"
    rounded
  >
    <slot
      v-for="(component, indexComponent) in components"
      :component="component"
      :index="indexComponent"
    />
  </v-sheet>
</template>

<script setup>
const props = defineProps({
  components: {
    type: Object,
    default: null,
  }
})

const { components } = toRefs(props);
</script>

<style scoped>

</style>
