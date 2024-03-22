<script setup lang="ts">
export interface Props {
  title?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: ''
})
const { title } = toRefs(props)
const { isMobile } = useDevice();
</script>
<template>
  <v-toolbar
    height="150"
    color="transparent"
  >
    <v-toolbar-title class="text-h6 font-weight-bold">
      <!-- <v-icon class="mr-2">mdi-account</v-icon> -->
      <h2
        :class="!isMobile ? 'text-h2 text-grey-darken-1' : 'text-h4 text-grey-darken-1'"
        align="center"
      >
        {{ title }}
      </h2>
    </v-toolbar-title>
  </v-toolbar>
</template>

<style scoped>

</style>
