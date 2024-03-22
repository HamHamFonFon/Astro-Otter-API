<script setup lang="ts">
import {defineAsyncComponent, ref} from "vue";
import type { Ref } from 'vue'

const inputSearchItems: Ref<string> = ref('');
const dsoList: SearchDsoItem[] = reactive([]);
const constellationsList: SearchConstellationItem[] = reactive([]);

const { data, pending } = await useSearchRequest(inputSearchItems);
const isDso = (item: SearchDsoItem | SearchConstellationItem ): boolean => {
  switch(item.context) {
    case 'App\\Model\\Dso': return true;
    case 'App\\Model\\Constellation': return false;
    default: return false;
  }
}

watchEffect(async () => {
  dsoList.length = 0;
  constellationsList.length = 0;
  data.value?.forEach((item: SearchDsoItem | SearchConstellationItem) => {
    if (isDso(item)) dsoList.push(item as SearchDsoItem);
    else constellationsList.push(item as SearchConstellationItem);
  });
});

const SearchListCard = defineAsyncComponent(() => import("@/components/Items/SearchListCard.vue"));
</script>

<template>
  <v-sheet
    elevation="0"
    class="mx-auto landing-warpper"
    rounded
    color="primary"
  >
    <v-sheet
      color="transparent"
      elevation="0"
      max-width="1600"
      class="mx-auto pa-5"
    >
      <v-container>
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            lg="3"
            style="align-self: baseline;"
          >
            <h1 class="text-h5 font-weight-bold text-center text-grey">
              {{ $t('search.seek') }}
            </h1>
          </v-col>
          <v-col
            cols="12"
            sm="12"
            md="9"
          >
            <v-text-field
              v-model="inputSearchItems"
              type="search"
              :label="$t('search.placeholder')"
              :placeholder="$t('search.placeholder')"
              rounded
              variant="outlined"
              append-inner-icon="mdi-magnify"
              clearable
            />
            <div v-if="true === pending">Load results...</div>
            <SearchListCard
              v-else
              :dsos="dsoList"
              :constellations="constellationsList"
            />
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </v-sheet>
</template>
