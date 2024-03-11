<template>
  <v-sheet elevation="0" class="mx-auto landing-warpper" rounded color="transparent">
    <v-sheet class="pa-3" elevation="0" color="transparent">
      <v-container>

        <!--  Filters-->
        <v-row>
          <v-col cols="12" :sm="getCountColumns(filtersBy)" v-for="(filtersByTypeData, type) in filtersBy" v-bind:key="type">
            <v-select
              v-model="selectedFilters[type]"
              :label="type"
              variant="outlined"
              :items="filtersByTypeData"
              item-title="label"
              item-value="name"
              @update:modelValue="fetchDsoList"
              clearable
            >
            </v-select>
          </v-col>
        </v-row>

        <!-- Sorts -->
        <v-row>
          <v-col align-self="start" cols="6" sm="6">
            <span class="text-white">{{ $t('catalogs.count', {'nbItems': nbItems, 'total': totalRef}) }}</span>
          </v-col>
        </v-row>

        <!-- Items list + cards -->
        <v-row align="center">
          <ItemsLists :items-list="items" :columns="3">
            <template v-slot="{ item, index }">
              <DsoCard v-bind:key="index" :dso="item" v-if="item" />
            </template>
          </ItemsLists>
        </v-row>

        <!-- Btn More -->
        <v-row align="center" justify="center">
          <BtnMoreItems
              v-if="nbItems < totalRef"
              @click-event="showMoreItems"
              :label="btnLabel"
              :icon="btnIcon"
              :btnLoading="btnLoading"
          ></BtnMoreItems>
        </v-row>

        <div :data-geojson="JSON.stringify(dsoGeoJson)"></div>

      </v-container>
    </v-sheet>
  </v-sheet>
</template>

<script setup>
import {computed, defineAsyncComponent, onBeforeMount, onMounted, ref, toRefs, watch} from "vue";
import {useRoute} from "vue-router";
import {useStore} from "vuex";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const route = useRoute();
const store = useStore();

// Services
import { saveShareLink } from '@/services/saveShareLink';
import {DsoWs} from "@/repositories/api/dso";
import { geoJsonServices } from '@/services/geojson';
import Trans from "@/services/translation"

// Components
const ItemsLists = defineAsyncComponent(() => import('@/components/Items/ItemsList.vue'));
const DsoCard = defineAsyncComponent(() => import('@/components/Items/DsoCard.vue'))
const BtnMoreItems = defineAsyncComponent(() => import('@/components/Content/btnMoreItems.vue'));

// Data
const items = ref([]);
const offset = ref(0);
const limit = ref(21);
const totalRef = ref(0);

const selectedFilters = ref({});
const filtersRef = ref([]);
const urlShare = ref(null);
const btnLabel = ref(t('layout.btnMore'))
const btnIcon = ref('mdi-plus');
const btnLoading = ref(false);

// Props
const props = defineProps({
  defaultFilterName: {
    type: String,
    default: null
  },
  defaultFilterValue: {
    type: String,
    default: null
  }
});
const { defaultFilterName, defaultFilterValue} = toRefs(props);

onBeforeMount(() => {
  selectedFilters.value = route.query;
})

// On mount
onMounted(() => {
  fetchDsoList();
})

// When changing locale
watch(() => Trans.currentLocale, () => {
  fetchDsoList();
});

// Methods
const fetchDsoList = async () => {
  try {
    const defaultFilters = {[defaultFilterName.value]: defaultFilterValue.value}
    let params = {
      ...defaultFilters,
      ...selectedFilters.value
    };
    const {data, filters, total} = await DsoWs.GET_DSO_LIST(params, 0, limit.value);
    items.value = data;
    filtersRef.value = filters;
    totalRef.value = total;
    offset.value = limit.value;
    urlShare.value = saveShareLink(route.path, params);
    // store.commit('message/setLoading', false);
  } catch (error) {
    store.commit('message/setMessage', {
      'loading': true,
      'type': 'error',
      'message': error.message,
      'httpCode': error.code
    }, { root: true })
  }
};

const showMoreItems = async  () => {
  btnLabel.value = t('dso.load_list');
  btnLoading.value = true;
  try {
    const defaultFilters = {[defaultFilterName.value]: defaultFilterValue.value}
    let params = {
      ...defaultFilters,
      ...selectedFilters.value
    };

    const { data, filters, total} = await DsoWs.GET_DSO_LIST(params, offset.value, limit.value);
    items.value = [...items.value, ...data]
    filtersRef.value = filters;
    totalRef.value = total;
    offset.value += limit.value;
    btnLabel.value = t('layout.btnMore');
  } catch (err) {
    store.commit('message/setMessage', {
      'loading': true,
      'type': 'error',
      'message': err.message,
      'httpCode': err.code
    }, { root: true })
  }
  btnLoading.value = false;
};

const getCountColumns = (filters) => 12/Object.keys(filters).length;

// Computed
const nbItems = computed(() => items.value.length);
const filtersBy = computed(() => {
  return Object.keys(filtersRef.value)
    .filter((type) => type !== defaultFilterName.value)
    .reduce((obj, key) => {
      return Object.assign(obj, {
        [key]: filtersRef.value[key]
      });
    }, {})
});
const dsoGeoJson = computed(() => geoJsonServices.geoJsonDso(items.value))
</script>

<style scoped>
</style>
