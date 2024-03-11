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
        <v-row align="center" justify="center">
          <v-col cols="12" lg="3" style="align-self: baseline;">
            <h1 class="text-h5 font-weight-bold text-center text-grey">
              {{ $t('search.seek') }}
            </h1>
          </v-col>
          <v-col cols="12" sm="12" md="9">
            <v-text-field
              v-model="inputSearchItems"
              type="search"
              :label="$t('search.placeholder')"
              :placeholder="$t('search.placeholder')"
              rounded
              variant="outlined"
              append-inner-icon="mdi-magnify"
              clearable="clearable"
            ></v-text-field>

            <SearchListCard :results="results"></SearchListCard>
<!--            <v-list v-if="results && 0 < results.length" class="listeResults" >-->
<!--              <v-list-item-->
<!--                v-for="item in results.dsos"-->
<!--                color="appBarColor"-->
<!--                v-bind:key="item"-->
<!--                left-->
<!--              >-->
<!--                <router-link :to="{ name: 'dso', params: { id: item.id } }">-->
<!--                  <v-list-item-title>-->
<!--                    {{ item.text }}-->
<!--                  </v-list-item-title>-->

<!--                  <v-list-item-subtitle>-->
<!--                    {{ item.type }}-->
<!--                  </v-list-item-subtitle>-->
<!--                </router-link>-->
<!--              </v-list-item>-->
<!--            </v-list>-->
          </v-col>
        </v-row>

      </v-container>
    </v-sheet>
  </v-sheet>
</template>

<script setup>
import {searchItems} from "@/services/autocompleteSearch";
import {defineAsyncComponent, ref, watch} from "vue";
const SearchListCard = defineAsyncComponent(() => import("@/components/Items/SearchListCard.vue"));

const inputSearchItems = ref('');
const results = ref([]);

watch(inputSearchItems, (newSearch) => {
  setTimeout(async () => {
    results.value = await searchItems(newSearch);
  }, 200);
});
</script>

<style scoped>

</style>
