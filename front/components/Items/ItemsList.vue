<template>
  <v-row class="grid" :style="{ gridTemplateColumns: getColumnStyle(), gridGap: getGapStyle() }">
    <slot
        v-for="item in itemsList"
        :item="item"
    >
    </slot>
  </v-row>
</template>

<script setup>
import {computed} from "vue";

const isMobile = computed(() => {
  return screen.width <= 766;
});


const props = defineProps({
  itemsList: {
    type: Object
  },
  gap: {
    type: Number,
    default: 0
  },
  columns: {
    type: Number,
    default: 0
  }
});

const getColumnStyle = () => {
  return (isMobile.value) ? `repeat(auto-fill, minmax(350px, 1fr))` :  `repeat(${props.columns}, 1fr)`;
}

const getGapStyle = () => {
  return `${props.gap}em`;
}
</script>

<style scoped>
:root {
  counter-reset: masonry;
}
.grid {
  display: grid;
}

.image-item {
  border-radius: 5px;
  overflow: hidden;
}
</style>