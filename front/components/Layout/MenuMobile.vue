<script setup>
  import {toRefs} from "vue";

  const props = defineProps({
    itemsMenu: {
      type: Object,
      default: null
    },
    btnColor: {
      type: String,
      default: ''
    },
    iconColor: {
      type: String,
      default: 'text-grey'
    },
    bgColor: {
      type: String,
      default: 'background'
    }
  });

  const { itemsMenu, btnColor, iconColor, bgColor } = toRefs(props);
</script>

<template>
  <v-menu
    :close-on-content-click="true"
    location="bottom right"
    transition="slide-y-transition"
  >
    <template v-slot:activator="{ props }">
      <v-btn icon v-bind="props" :color="btnColor">
        <v-icon :color="iconColor" icon="mdi-menu"></v-icon>
      </v-btn>
    </template>
    <v-card max-width="400">
      <v-list nav v-for="item in itemsMenu" v-bind:key="item" :bg-color="bgColor">
        <v-list-item link :to="item.path">
          <v-list-item-title class="mt-2">{{ item.text }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </v-menu>

</template>

<style scoped>

</style>
