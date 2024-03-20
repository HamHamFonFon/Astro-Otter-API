<template>
  <v-row
    class="grid"
    :style="{ gridTemplateColumns: getColumnStyle(), gridGap: getGapStyle() }"
  >
    <slot
      v-for="item in constellationList"
      :item="item"
    />
  </v-row>
</template>

<script setup lang="ts">
const { isMobile } = useDevice();

const props = withDefaults(defineProps<Props>(), {
  dsoList: () => [],
  constellationList: () => [],
  gap: 0,
  columns: 0
});

interface Props {
  dsoList?: Dso[] | null,
  constellationList?: Constellation[] | null,
  gap?: number,
  columns?: number
}

const { dsoList, constellationList, gap, columns } = toRefs(props);
const itemsList = () => {
  // Directly return listA if not null and has elements
  if (dsoList && dsoList.value !== null && dsoList.value.length > 0) {
    return dsoList;
  }

  // Return listB if not null and has elements
  return constellationList && constellationList.value !== null && constellationList.value.length > 0 ? constellationList : dsoList;
};
const validList = computed(() => itemsList());

const getColumnStyle = () => {
  return (isMobile) ? `repeat(auto-fill, minmax(350px, 1fr))` :  `repeat(${columns.value}, 1fr)`;
}

const getGapStyle = () => `${gap.value}em`;
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
