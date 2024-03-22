<template>
  <v-row
    class="grid"
    :style="{ gridTemplateColumns: getColumnStyle(), gridGap: getGapStyle() }"
  >
    <slot
      v-for="item in list"
      :item="item"
    />
  </v-row>
</template>

<script setup lang="ts" generic="T">
const { isMobile } = useDevice();

const props = withDefaults(defineProps<Props>(), {
  list: () => [],
  gap: 0,
  columns: 0
});

interface Props {
  list?: T[],
  gap?: number,
  columns?: number
}

const { list, gap, columns } = toRefs(props);

const getColumnStyle = (): string => {
  return (isMobile) ? `repeat(auto-fill, minmax(350px, 1fr))` :  `repeat(${columns.value}, 1fr)`;
}

const getGapStyle = (): string => `${gap.value}em`;
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
