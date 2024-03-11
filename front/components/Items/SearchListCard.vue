<script setup>
import { toRefs } from "vue";

const props = defineProps({
  results: {
    type: Object,
    default: null
  }
});
const { results } = toRefs(props);

const emit = defineEmits(['click-clear']);
const clickClear = () => {
  emit('click-clear');
}

const getParams = (item) => {
  let params = {
    id: item.id
  };

  if (null !== item.urlName) {
    params.urlName = item.urlName;
  }

  return params;
}
</script>

<template>

  <v-list lines="two" bg-color="transparent" v-if="results && 0 < results.nbItems">
    <v-list-subheader inset color="grey">Objects</v-list-subheader>
    <v-list-item
      color="transparent"
      v-for="item in results.dsos"
      :key="item"
      @click="clickClear"
    >
      <router-link :to="{ name: 'dso', params: getParams(item) }">
        <v-list-item-title v-text="item.text"></v-list-item-title>
        <v-list-item-subtitle v-text="item.text"></v-list-item-subtitle>
      </router-link>
    </v-list-item>

    <v-divider inset></v-divider>

    <v-list-subheader color="grey" inset>{{ $t('constellations.title')}}</v-list-subheader>
    <v-list-item
      color="transparent"
      v-for="item in results.constellations"
      :key="item"
      @click="clickClear"
    >
<!--      <template v-slot:prepend>-->
<!--        <v-avatar :image="`@/assets/images/constellations/cover/${item.cover}`"></v-avatar>-->
<!--      </template>-->
      <router-link :to="{ name: 'constellation', params: { constellationId: item.id, urlName: item.urlName } }">
        <v-list-item-title v-text="item.text"></v-list-item-title>
      </router-link>
    </v-list-item>
  </v-list>
</template>

<style scoped>
.v-list-item:hover {
  background: #1ed760;
  color: #1B2A32;
}
</style>
