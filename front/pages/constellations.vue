<script setup lang="ts">
import { definePageMeta, useI18n } from '#imports';
import { ref } from "vue";

const { t, locale } = useI18n();
import backgroundConstellationImage from '@/assets/images/background/constellations.jpg';

const Message = defineAsyncComponent(() => import('@/components/Layout/Message.vue'));
const TitleImageHero = defineAsyncComponent(() => import('@/components/Content/TitleImageHero.vue'));
const FilterList = defineAsyncComponent(() => import('@/components/Content/FilterList.vue'));
const ItemsLists = defineAsyncComponent(() => import('@/components/Items/ItemsList.vue'));
const ConstellationCard = defineAsyncComponent(() => import('@/components/Items/ConstellationCard.vue'));

definePageMeta({
  layout: 'page'
});

applySeo({
  title: t('constellations.title'),
  description: t('constellations.description'),
  image: backgroundConstellationImage,
  fullUrl: ''
});

const backgroundImage = ref(backgroundConstellationImage);
const filterConstellation: Ref<string> = ref('');
const nbColumns: Ref<number> = ref(4)

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('constellation.load.list');

const {
  data,
  pending,
  error,
  status,
  refresh
} = await useCustomFetch<Constellation[]>('/constellation/list', {
  method: 'GET'
})

if ("success" === status.value) {
  type.value = 'success';
  message.value = t('constellation.load.loaded')
} else {
  type.value = 'error';
  message.value = error.value?.message
}

watch(locale, () => refresh());

</script>

<template>
  <DelayHydration>
    <TitleImageHero
      :title="t('constellations.title')"
      :url-image="backgroundImage"
    />

    <transition
      v-if="pending"
      name="fade"
    >
      <Message />
    </transition>
    <transition
      v-else-if="error"
      name="fade"
    >
      <Message />
    </transition>

    <transition
      v-else
      name="fade"
    >
      <v-sheet
        elevation="0"
        class="landing-warpper"
        color="transparent"
      >
        <v-container class="text-left">
          <FilterList
            v-model="filterConstellation"
            :label="$t('constellation.filter.label')"
            :placeholder="$t('constellation.filter.placeholder')"
          />

          <v-sheet
            elevation="0"
            class="mx-auto landing-warpper"
            rounded
            color="transparent"
          >
            <v-sheet
              class="pa-3"
              elevation="0"
              color="transparent"
            >
              <v-container>
                <v-row align="center">
                  <ItemsLists
                    :constellation-list="data"
                    :columns="nbColumns"
                  >
                    <template #default="{ item }">
                      <ConstellationCard
                        :key="item.id"
                        :constellation="item"
                      />
                    </template>
                  </ItemsLists>
                </v-row>
              </v-container>
            </v-sheet>
          </v-sheet>
        </v-container>
      </v-sheet>
    </transition>
  </DelayHydration>
</template>

<style scoped>
</style>
