<template>
  <v-breadcrumbs :items="items">
    <template #divider>
      <v-icon icon="mdi-chevron-right" />
    </template>
  </v-breadcrumbs>
</template>

<script>
export default {
  name: "BreadCrumb",
  data() {
    return {
      items: ['Home', 'List', 'Page']
    }
  }
}
</script>

<style scoped>

</style>
