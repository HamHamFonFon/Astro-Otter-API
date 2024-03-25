<template>
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
            v-for="(filtersByTypeData, type ) in listFilters"
            :key="type"
            cols="12"
            :sm="getCountColumns"
          >
            {{ type }} {{ JSON.stringify(filtersByTypeData) }}
<!--            <v-select-->
<!--              v-model="selectedFilters[type]"-->
<!--              :label="type as string"-->
<!--              variant="outlined"-->
<!--              :items="filtersByTypeData"-->
<!--              item-title="label"-->
<!--              item-value="name"-->
<!--              clearable-->
<!--              @update:model-value="fetchDsoList"-->
<!--            />-->
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
<!--              {{ $t('catalogs.count', {'nbItems': nbItems, 'total': totalRef}) }}-->
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

<!--        <div :data-geojson="JSON.stringify(dsoGeoJson)" />-->
      </v-container>
    </v-sheet>
  </v-sheet>
</template>

<script setup lang="ts">
import {computed, defineAsyncComponent, onBeforeMount, onMounted, type Ref, ref, toRefs} from "vue";

const { t } = useI18n();
const route = useRoute();

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('constellation.load.data');

// Services
// import { saveShareLink } from '@/services/saveShareLink';
// import { geoJsonServices } from '@/services/geojson';

// Components
const ItemsLists = defineAsyncComponent(() => import('@/components/Items/ItemsList.vue'));
const DsoCard = defineAsyncComponent(() => import('@/components/Items/DsoCard.vue'))
const BtnMoreItems = defineAsyncComponent(() => import('@/components/Content/btnMoreItems.vue'));

// Data
const listDso: Dso[] = reactive([]);
const offset: Ref<number> = ref(0);
const limit: Ref<number> = ref(21);
const totalRef: Ref<number> = ref(0);

const selectedFilters = ref({});
const listFilters: Filters[] = reactive([]);
// const urlShare = ref(null);
const btnLabel: Ref<string> = ref(t('layout.btnMore'))
const btnIcon: Ref<string> = ref('mdi-plus');
const btnLoading: Ref<boolean> = ref(false);

const props = withDefaults(defineProps<Props>(), {
  defaultFilterName: '',
  defaultFilterValue: '',
});
export interface Props {
  defaultFilterName?: string
  defaultFilterValue?: string
}

// Props
const { defaultFilterName, defaultFilterValue} = toRefs(props);

onBeforeMount(() => {
  selectedFilters.value = route.query;
})

// On mount
onMounted(() => {
  fetchDsoList();
})

// Methods
const fetchDsoList = async () => {
  const defaultFilters = {[defaultFilterName.value]: defaultFilterValue.value}
  const requestParams = {
    ...defaultFilters,
    ...selectedFilters.value,
    ...{
      offset: offset.value,
      limit: limit.value
    }
  };

  const filteredRequestParams = Object.entries(requestParams).reduce((x, [k, v]) => {
    if (v || 0 === v) { // not ( null, undefined, empty string)
      x[k] = v;
    }
    return x;
  }, {} as any);

  const {
    data,
    error
  } = useCustomFetch('/dso/list', {
    method: 'GET',
    params: filteredRequestParams
  });

  // if (error)
  const { filters, items, total }: { filters: Filters, items: Dso[], total: number} = data.value;
  listFilters.push(filters)
  listDso.push(...items);
  totalRef.value = total;
  offset.value = limit.value;
  // urlShare.value = saveShareLink(route.path, params);
};

const showMoreItems = async  () => {
  btnLabel.value = t('dso.load_list');
  btnLoading.value = true;

    const defaultFilters = {[defaultFilterName.value]: defaultFilterValue.value}
    const params = {
      ...defaultFilters,
      ...selectedFilters.value
    };

    // const { data, filters, total} = //await DsoWs.GET_DSO_LIST(params, offset.value, limit.value);
    // items.value = [...items.value, ...data]
    // filtersRef.value = filters;
    // totalRef.value = total;
    // offset.value += limit.value;
    btnLabel.value = t('layout.btnMore');

  btnLoading.value = false;
};

const getCountColumns = (): number => 12 / Object.keys(listFilters).length;

// Computed
const nbItems = computed((): number => listDso.length);
const filtersBy = computed(() => {
  return Object.keys(listFilters)
    .filter((type) => type !== defaultFilterName.value)
    .reduce((obj, key) => {
      return Object.assign(obj, {
        [key]: listFilters[key]
      });
    }, {})
});

// const dsoGeoJson = computed(() => geoJsonServices.geoJsonDso(items.value))
</script>

<style scoped>
</style>
