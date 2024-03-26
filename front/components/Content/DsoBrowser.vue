<template>
  <transition
    v-if="pending"
    name="transition"
  >
    <Message />
  </transition>
  <transition
    v-else-if="response"
    name="fade"
  >
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
          <!--  Filters-->
          <v-row>
            <v-col
              v-for="(filtersByTypeData, label) in filtersBy"
              :key="label"
              cols="12"
              :sm="getCountColumns"
            >
              <v-select
                v-model="selectedFilters[label]"
                :label="`${label}`"
                :items="filtersByTypeData"
                variant="outlined"
                item-title="label"
                item-value="name"
                clearable
              />
            </v-col>
          </v-row>

          <!-- Sorts -->
          <v-row>
            <v-col
              align-self="start"
              cols="6"
              sm="6"
            >
              <span class="text-white">
                {{ nbItems }} / {{ totalRef }}
              </span>
            </v-col>
          </v-row>

          <!-- Items list + cards -->
          <v-row align="center">
            <ItemsLists
              :list="listDso"
              :columns="getCountColumns"
            >
              <template #default="{ item }: { item: Dso }">
                <DsoCard
                  :dso="item"
                />
              </template>
            </ItemsLists>
          </v-row>

          <!-- Btn More -->
          <v-row
            align="center"
            justify="center"
          >
            <BtnMoreItems
              v-if="nbItems < totalRef"
              :label="btnLabel"
              :icon="btnIcon"
              :btn-loading="btnLoading"
              @click-event="showMoreItems"
            />
          </v-row>

          <div :data-geojson="JSON.stringify(dsoGeoJson)" />
        </v-container>
      </v-sheet>
    </v-sheet>
  </transition>
</template>

<script setup lang="ts">
/**
 * Composables
 */
import {computed, defineAsyncComponent, type Ref, ref, toRefs} from "vue";
import {useGeoJsonServices} from "~/composables/useGeoJsonServices";
import {definePageMeta} from "#imports";
import backgroundConstellationImage from "assets/images/background/constellations.jpg";

const { t } = useI18n();
const route = useRoute();
const { geoJsonDso } = useGeoJsonServices();
const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('layout.load');

/**
 * SEO
 */
definePageMeta({
  layout: 'page'
});

applySeo({
  title: t('catalogs.title'),
  description: t('catalogs.description'),
  image: backgroundConstellationImage,
  fullUrl: ''
});
// Services
// import { saveShareLink } from '@/services/saveShareLink';

/**
 * Components
 */
const Message = defineAsyncComponent(() => import('@/components/Layout/Message.vue'));
const ItemsLists = defineAsyncComponent(() => import('@/components/Items/ItemsList.vue'));
const DsoCard = defineAsyncComponent(() => import('@/components/Items/DsoCard.vue'))
const BtnMoreItems = defineAsyncComponent(() => import('@/components/Content/btnMoreItems.vue'));

/**
 * Ref & Reactive
 */
const listDso: Dso[] = reactive([]);
const offset: Ref<number> = ref(0);
const limit: Ref<number> = ref(21);
const totalRef: Ref<number> = ref(0);

const selectedFilters: Ref<Record<string, string>> = ref({});
const requestParams: Ref<Record<string, string | number>> = ref({});
const btnLabel: Ref<string> = ref(t('layout.btnMore'))
const btnIcon: Ref<string> = ref('mdi-plus');
const btnLoading: Ref<boolean> = ref(false);
// const urlShare: Ref<string> = ref('');

/**
 * Props
 */
export interface Props {
  defaultFilterName?: string
  defaultFilterValue?: string
}
const props = withDefaults(defineProps<Props>(), {
  defaultFilterName: '',
  defaultFilterValue: '',
});

const { defaultFilterName, defaultFilterValue} = toRefs(props);
console.info(route.query)
/**
 * Query parameters
 */
const defaultFilters = {[defaultFilterName.value]: defaultFilterValue.value}
requestParams.value = {
  ...defaultFilters, // Filter by default,
  ...selectedFilters.value, // filters selected by user
  ...route.query, // route query params
  ...{
    offset: offset.value,
    limit: limit.value
  }
};


/**
 * Request and results
 */
console.clear(); console.error(requestParams.value);
const { data: response, pending, error, refresh } = useCustomFetch<BrowserResponse>('/dso/list', {
  method: 'GET',
  params: Object.entries(requestParams.value).reduce((x, [k, v]) => {
    if (v || 0 === v) { // not ( null, undefined, empty string)
      x[k] = v;
    }
    return x;
  }, {} as any),
  watch: [requestParams]
});

if (null !== error.value) {
  pending.value = true;
  type.value = 'error';
  message.value = `Error while loading page: ${error.value?.message}`;
}

response.value?.items?.forEach((d: Dso) => listDso.push(d));
totalRef.value = response.value?.total as number;
offset.value = limit.value;

/**
 * Computed
 */
const getCountColumns = computed<number>(() => {
  return 12 / Object.keys(response.value?.filters ?? []).length;
});
const nbItems = computed<number>((): number => listDso.length);
const dsoGeoJson = computed(() => geoJsonDso(listDso))
/**
 * source https://www.typescriptlang.org/play?#code/JYOwLgpgTgZghgYwgAgCIGcD2yDeAoZZYAEwC5l0wpQBzAGgOQgBs5LgEBlCOKBACwCSZClVoNCzTAjjMI5StRD08AXzx5QkWIhQA1WQFcIAMWDNtuRgkyHwAfnIhDAWwBG0CchBwXER6JKKpJwHswBirRqGlrQ8EjIZhbQ6FaENiCULKxgwJggAQbMxknaANoAul4yYLKYNIVGpublVYwucDQgwGCGxP7kRSUt0JVeYACeAA4DyEPNyVCV0ZrgcbrIAEJQmADu6NAAShDoU-kHaUSQLugBGJhjjDAjULfkOGWTMwpiysgV5FKKUqyFU40wtXCTlcHigyAAPsg7P1niAIMQVhlKMgoCczpl5FsdvsjnjzigALyXHoQG7kMqMQj4Qgs5AAIikMjkbPIbIgIDZXlZfNY7C4PD4QmIPPZAAYYABmACMcAAbAAWWVuADssoAnABWQ1K4ja7UIGDqngQdUK21uVUIQWMllskgytnofgAJgAtEqDQbnaywS7maz2ZzZBAPfzgxGRWxcuLeAJhLGEG51QgDUqtTADW4ABx62VIVV67UwGBFtwKhAIYgKvWq1Xx4Xu3lev1K71toWggfh4VR7m8uMDwiJsXcVNSj3emDmnhuOBwWVwYiL4hKgvaosIXcwCAm02y9UwduuzuRuCTA4ANyVV-UhDahGei3Q7xdWMgzByPIQHpF0mVAlkfD8XkH2AKAr1ZGw7DAchdT1ScWVYMJeT0WCaEwNlwNDCNLmItlIJjXlXDgeDXUQ8AZXVIslXQqdMJYD0AFVXjgZAAFk4AAK0wODCPQ4cE1kMAPSQKSWPZOipPIdUlXVOSOVCdjeQAYQgXp0AI4jXxZd8WRqOoaBA4jxOFciPWUJ01IUmVtW9IsizUtjmA9AA5CBdmQABxfloFkZAtLvcyDIjIiI2s11bN5DgaKnJylIVBUPI0rzeUEEB+gADzCiKpBoKKQzE8CpwS+S4Mc2x6PIBUDW9TKwg9LTMAA0B+hEwyKuIqrfAo9lDBoBzKvk+rFOQb0W1azT2Q4qYpnQWQeKCtEoFC8LIXqMqWSMt8Bw6Loej6QkGSsiayKGj0pF2ZLJqQ5zZXm7L2RMHYXGQWVkDAbAg1E8C4sGvwPTgB8QpoGM6uelCFRa67PI9T7MG+g0-uwPN9sIGLWRB9lqrZfhgBofhHrZVLkFVI0MqRrKUa+5A80x5nAb64HrqJ-heGlWGGpm719Tej0A2QOA8uQFxhJjQiXRMwgvguzmBsJ26uwpqnlO1A0Rd5Tgplg0KaDW-KJhxwcVYGm6wfHTWpplXsDVe+m2t5ABRLqpmTY3TfNoGrtVm3hs9WV7bh5mlWVPX2QAGX5ZNDFYOETdYM2LcO-5GBitl-shGUWxUtQAG4ND-ZAUTgJOwCBKBvKG5AqQAcibsvzjAZBP20dBNgmRvkAAeTcASIAQMAADoAGsIAmdAAApcVOclx67lIEURHBVAASkYcfcWIQwkDnufEAQOhkGniYt8bgA+EiiBgZA58v5AAEIKSpSvq9r+u-GvuLT5lEvhUfui98QHBXi8dAr8gEzwqC6dQLpcS9CgCAcWDZS643PjgUE4tUi13QDvPAWJOoQHHiVOeq9Xi9y3kAA
 */
const filtersBy = computed(() => {
  return Object.keys(response?.value?.filters || {})
    .reduce<{[key: string ]: Filters[] }>((acc, key) => {
      if (key !== defaultFilterName.value) {
        acc[key] = response?.value?.filters![key]
      }

      return acc;
    }, { })
});

/**
 * Functions
 */
const showMoreItems = () => {
  offset.value += limit.value;
  refresh();

//   while (pending) {
//     btnLabel.value = t('dso.load_list');
//     btnLoading.value = true;
//   }
//
// //   setTimeout(() => {
// //     refresh();
// //     btnLabel.value = t('layout.btnMore');
// //     btnLoading.value = false;
// //   }, 2000)
// //
}

</script>

<style scoped>
</style>
