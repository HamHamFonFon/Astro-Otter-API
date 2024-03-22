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
<!--          <v-col-->
<!--            v-for="(filtersByTypeData, type ) in filtersBy"-->
<!--            :key="type"-->
<!--            cols="12"-->
<!--            :sm="getCountColumns(filtersBy)"-->
<!--          >-->
<!--            <v-select-->
<!--              v-model="selectedFilters[type]"-->
<!--              :label="type"-->
<!--              variant="outlined"-->
<!--              :items="filtersByTypeData"-->
<!--              item-title="label"-->
<!--              item-value="name"-->
<!--              clearable-->
<!--              @update:model-value="fetchDsoList"-->
<!--            />-->
<!--          </v-col>-->
        </v-row>

        <!-- Sorts -->
        <v-row>
          <v-col
            align-self="start"
            cols="6"
            sm="6"
          >
<!--            <span class="text-white">{{ $t('catalogs.count', {'nbItems': 0, 'total': 9000}) }}</span>-->
          </v-col>
        </v-row>

        <!-- Items list + cards -->
        <v-row align="center">
          <ItemsLists
            :list="items"
            :columns="3"
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
import {useCheckTypeItem} from "~/composables/useCheckTypeItem";

const { t } = useI18n();
const route = useRoute();

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('constellation.load.data');

// const { isDso, isConstellation } = useCheckTypeItem();

// Services
// import { saveShareLink } from '@/services/saveShareLink';
// import {DsoWs} from "@/repositories/api/dso";
// import { geoJsonServices } from '@/services/geojson';

// Components
const ItemsLists = defineAsyncComponent(() => import('@/components/Items/ItemsList.vue'));
const DsoCard = defineAsyncComponent(() => import('@/components/Items/DsoCard.vue'))
const BtnMoreItems = defineAsyncComponent(() => import('@/components/Content/btnMoreItems.vue'));

// Data
const items: Dso[] = reactive([]);
const offset: Ref<number> = ref(0);
const limit: Ref<number> = ref(21);
const totalRef: Ref<number> = ref(0);

const selectedFilters = ref({});
const filtersRef = ref([]);
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

  const {
    data,
    pending,
    error
  } = useCustomFetch('/dso/list', {
    method: 'GET',
    params: requestParams
  });

  const { items, filters, total} = data.value;
  console.log(items);
  console.log(filters);
  console.log(total);

    // const {da
    //
    // ta, filters, total} = await DsoWs.GET_DSO_LIST(params, 0, limit.value);
    // items.value = data;
    // filtersRef.value = filters;
    // totalRef.value = total;
    // offset.value = limit.value;
    // urlShare.value = saveShareLink(route.path, params);
};

const showMoreItems = async  () => {
  btnLabel.value = t('dso.load_list');
  btnLoading.value = true;
  try {
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
  } catch (err) {
    // store.commit('message/setMessage', {
    //   'loading': true,
    //   'type': 'error',
    //   'message': err.message,
    //   'httpCode': err.code
    // }, { root: true })
  }
  btnLoading.value = false;
};

// const getCountColumns = (filters) => 12/Object.keys(filters).length;
//const getCountColumns = (filters: Record<string, any>): number => 12 / Object.keys(filters).length;

// Computed
const nbItems = computed((): number => items.length);
/*const filtersBy = computed(() => {
  return Object.keys(filtersRef.value)
    .filter((type: string) => type !== defaultFilterName.value)
    .reduce((obj: { [key: string]: any }, key: string) => {
      return { ...obj, [key]: filtersRef.value[key] };
    }, {});
});*/

// const dsoGeoJson = computed(() => geoJsonServices.geoJsonDso(items.value))
</script>

<style scoped>
</style>
