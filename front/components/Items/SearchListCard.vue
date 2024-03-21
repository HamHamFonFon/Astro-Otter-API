<script setup lang="ts">
import { toRefs } from "vue";
const props = defineProps<{
  dsos: SearchDsoItem[],
  constellations: SearchConstellationItem[]
}>()

const { dsos, constellations } = toRefs(props);

const emit = defineEmits(['click-clear']);
const clickClear = () => {
  emit('click-clear');
}

const getParams = (item: SearchDsoItem | SearchConstellationItem) => {
  const params: {id: string, urlName?: string} = {
    id: item.id
  };

  if (null !== item.urlName) {
    params.urlName = item.urlName;
  }

  return params;
}
</script>

<template>
  <v-list
    v-if="dsos && 0 < dsos.length"
    lines="two"
    bg-color="transparent"
  >
    <v-list-subheader
      inset
      color="grey"
    >
      Objects
    </v-list-subheader>
    <v-list-item
      v-for="(item, index) in dsos"
      :key="index"
      color="transparent"
      @click="clickClear"
    >
      <NuxtLink :to="{ name: 'dso-id-urlName', params: getParams(item) }">
        <v-list-item-title>{{ item.fullNameAlt }}</v-list-item-title>
        <v-list-item-subtitle>{{ item.typeLabel }}</v-list-item-subtitle>
      </NuxtLink>
    </v-list-item>

    <v-divider inset />

    <v-list-subheader
      color="grey"
      inset
    >
      {{ $t('constellations.title') }}
    </v-list-subheader>
    <v-list-item
      v-for="(item, index) in constellations"
      :key="index"
      color="transparent"
      @click="clickClear"
    >
      <NuxtLink :to="{ name: 'constellation-id-urlName', params: { id: item.id, urlName: item.urlName } }">
        <v-list-item-title /> {{ item.alt }}
      </NuxtLink>
    </v-list-item>
  </v-list>
</template>

<style scoped>
.v-list-item:hover {
  background: #1ed760;
  color: #1B2A32;
}
</style>
