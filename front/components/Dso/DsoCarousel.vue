<template>
  <v-sheet
    elevation="1"
    class="mx-auto landing-warpper"
    rounded
  >
    <v-carousel
      hide-delimiter-background
      show-arrows="hover"
    >
      <v-carousel-item
        v-for="image in galleryImages"
        :key="image.id"
        color="background"
        :src="image.urlHd"
        height="100%"
        cover
      >
        <v-row
          class="title-slider ma-0"
          align="start"
        >
          <div class="display-3 text-white">
            {{ $t('dso.carousel', {'title': image.title, 'user': image.user}) }}
          </div>
        </v-row>
      </v-carousel-item>
    </v-carousel>
  </v-sheet>
</template>

<script setup>
defineProps({
  galleryImages: {
    type: Object,
    default: null
  }
})

</script>

<style scoped>
.title-slider {
  background-color: rgba(0, 0, 0, 0.7);
}
</style>
