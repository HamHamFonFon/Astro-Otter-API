<template>
  <v-hover v-slot:default="{ isHovering, props }">
    <v-card
      class="ma-3"
      color="secondary"
      :elevation="isHovering ? 18 : 1"
      v-bind="props"
  >
    <a :href="astrobinImageUrl(item.id)" target="_blank">
      <v-img
          :src="item.image"
          :lazy-src="item.lazyImage"
          :class="isHovering ? 'zoom bg-grey-lighten-2': 'bg-grey-lighten-2'"
          height="340"
          cover
      >
        <template v-slot:placeholder>
          <v-row
              class="fill-height ma-0"
              align="center"
              justify="center"
          >
            <v-progress-circular
                indeterminate
                color="grey-lighten-5"
            ></v-progress-circular>
          </v-row>
        </template>
        <v-expand-transition>
          <div class="d-flex transition-fast-in-fast-out text-white v-card--reveal display-3 white--text" style="height: 100%;">
            <v-card-title class="text-center text-h6 text-white">
              <p>{{ item.title }}</p>
            </v-card-title>
          </div>
        </v-expand-transition>
      </v-img>
    </a>
    <v-card-actions color="primary">
      <v-list-item class="w-100">
        <template v-slot:prepend>
          <v-icon color="grey" icon="mdi-account" />{{ item.user }}
        </template>
        <template v-slot:append>
          <div class="justify-self-end">
            <v-icon icon="mdi-eye" color="grey"></v-icon> <span class="subheading me-2">{{ item.views }}</span>
            <v-icon icon="mdi-heart" color="grey"></v-icon> <span class="subheading me-2">{{ item.likes }}</span>
          </div>
        </template>
      </v-list-item>
    </v-card-actions>
  </v-card>
  </v-hover>
</template>

<script setup>
import api from "@/configs/api";

defineProps({
  item: {
    type: Object
  }
})

const astrobinImageUrl = (imageId) => {
  return api.ASTROBIN_HOST + '/' + imageId;
}
</script>

<style scoped>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: .5;
  position: absolute;
  width: 100%;
}

.v-card-title {
  background-color: rgba(0, 0, 0, 0.5);
}
.v-img {
  border-bottom: solid #1ed760;
}
.v-img:hover {
  cursor: pointer;
}
</style>
