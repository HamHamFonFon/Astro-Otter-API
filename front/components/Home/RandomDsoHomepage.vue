<template>
  <v-sheet elevation="0" class="mx-auto landing-warpper" rounded color="primary">
    <v-sheet max-width="1600" class="mx-auto pa-5" elevation="0" color="transparent">
      <v-container>
        <v-row align="center">
          <v-col cols="12" md="4" v-for="item in state.items.value" :key="item.id">
            <DsoCard :dso="item" />
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </v-sheet>
</template>

<script setup>
import DsoCard from "@/components/Items/DsoCard.vue";
import {DsoWs} from "@/repositories/api/dso";
import {onMounted, reactive, watch} from "vue";
import Trans from "@/services/translation";
const state = reactive({ items: {}});

onMounted(() => {
  getRandomDso();
});

watch(() => Trans.currentLocale, () => {
  getRandomDso();
});

const getRandomDso = async () => {
  try {
    state.items.value = await DsoWs.GET_RANDOM(0, 3);
  } catch (err) {
    console.error(err.message);
  }
}

</script>

<style scoped>

</style>
