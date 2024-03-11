<template>
  <v-breadcrumbs :items="items">
    <template v-slot:divider>
      <v-icon icon="mdi-chevron-right"></v-icon>
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