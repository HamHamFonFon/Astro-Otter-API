<template>
  <v-sheet
      elevation="1"
      class="mx-auto landing-warpper"
      rounded
  >
    <v-card class="card-shadow" color="secondary">
      <v-card-title>
        <v-icon icon="mdi-camera-account" class="mr-2" left></v-icon>
        {{ $t('dso.astrobin.user') }}
      </v-card-title>
      <v-divider></v-divider>
      <v-list-item
          v-for="(item, i) in listDataUser()"
          :key="i"
          :value="item"
          color="primary"
      >
        <template v-slot:prepend>
          <v-icon :icon="item.icon"></v-icon>
        </template>

        <v-list-item-title justify>
          {{ item.text }}
        </v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>
      <v-card-title>
        <v-icon icon="mdi-data-matrix" class="mr-2" left></v-icon>
        {{ $t('dso.astrobin.data') }}
      </v-card-title>
      <v-divider></v-divider>
      <v-list-item
          v-for="(item, i) in listItems()"
          :key="i"
          :value="item"
          color="primary"
      >
        <template v-slot:prepend>
          <v-icon :icon="item.icon"></v-icon>
        </template>

        <v-list-item-title justify>
          {{ item.text }}
        </v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>
      <v-card-text>
        {{ astrobinImage.description }}
      </v-card-text>
      <v-card-item>
        <div class="image__chips" v-if="0 < astrobinImage.subjects.length">
          <v-chip-group>
            <v-chip v-for="chip in astrobinImage.subjects" :key="chip">{{ chip }}</v-chip>
          </v-chip-group>
        </div>
      </v-card-item>
      <v-card-actions>
        <v-btn @click="astrobinPageUrl">{{ $t('dso.astrobin.page')}}</v-btn>
      </v-card-actions>
    </v-card>
  </v-sheet>


</template>

<script setup>
import { toRefs} from "vue";
import apiConfig from '@/configs/api';

const props = defineProps({
  astrobinId: {
    type: String,
    default: null
  },
  astrobinImage: {
    type: Object,
    default: null
  },
  astrobinUser: {
    type: Object,
    default: null
  }
});

const { astrobinId, astrobinImage, astrobinUser } = toRefs(props);

const listDataUser = () => {
  return [
    { text: astrobinUser.value.username, icon: 'mdi-account'},
    { text: astrobinUser.value.job ?? null, icon: 'mdi-briefcase'},
    { text: astrobinUser.value.hobbies ?? null, icon: 'mdi-pine-tree'},
    { text: astrobinUser.value.website ?? null, icon: 'mdi-web'},
  ].filter(obj => obj.text !== '' && obj.text !== null);
}

const listItems = () => {
  return [
    { text: astrobinImage.value.likes ?? 0, icon: 'mdi-heart'},
    { text: astrobinImage.value.views ?? 0, icon: 'mdi-eye'},
    { text: convertDate(astrobinImage.value.uploaded.timestamp), icon: 'mdi-clock'},
    { text: astrobinImage.value.ra ?? null, key: 'RA'},
    { text: astrobinImage.value.dec ?? null, key: 'Dec'},
    { text: astrobinImage.value.scale ?? null, key: 'Pixel scale'},
    { text: astrobinImage.value.orientation ?? null, key: 'Orientation'},
    { text: astrobinImage.value.radius ?? null, key: 'Field radius'},
  ].filter(obj => obj.text !== '' && obj.text !== null &&  obj.text !== undefined);
};

const astrobinPageUrl = () => {
  let astrobinUrl = apiConfig.ASTROBIN_HOST + '/' + astrobinId.value + '/';
  window.open(astrobinUrl, '_blank');
}

const convertDate = (timestamp) => {
  const currentDate = new Date(timestamp*1000);
  return currentDate.getDate()+
      "/"+(currentDate.getMonth()+1)+
      "/"+currentDate.getFullYear();
};
</script>

<style scoped>

</style>
