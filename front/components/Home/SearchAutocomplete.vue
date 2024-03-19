<script setup lang="ts">
import {defineAsyncComponent, ref, watch} from "vue";

const inputSearchItems = ref('');
const results = ref([]);
const REGEX = new RegExp('/^[a-zA-Z0-9&\\-_;: ]+$/gm');

watch(inputSearchItems, (newSearch: string) => {
  setTimeout(async () => {
    if (2 <= newSearch.length && !REGEX.test(newSearch)) {
      const { data, pending, error, refresh } = await useSearchRequest(newSearch);
      console.log(data, pending, error);
    }
  }, 200);
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
            <div v-if="pending">Loading...</div>
            <SearchListCard v-else :results="results" />
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </v-sheet>
</template>
