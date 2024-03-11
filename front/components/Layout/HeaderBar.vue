<template>
  <v-app-bar
    color="secondary"
    mode="shift"
    :density="!isMobile ? 'default' : 'compact'"
  >
    <v-toolbar color="secondary">
      <router-link :to="{ name: 'home'}" v-bind:title="t('layout.homeAccess')">
        <v-avatar class="mx-2">
          <v-img :src="logo" :alt="t('layout.logo')"></v-img>
        </v-avatar>
      </router-link>
      <v-divider vertical thickness="2" inset :class="!isMobile ? 'ml-5 mr-1' : 'mr-1'"></v-divider>
      <div v-if="!isMobile">
        <v-btn v-for="(menuItem, index) in processedMenu(menu, props.allRoutes)" stacked="" v-bind:key="index" class="text-none">
          <router-link :to="menuItem.path">
            <span class="text-grey">{{ menuItem.text }}</span>
          </router-link>
        </v-btn>
      </div>

      <v-spacer></v-spacer>
      <Transition>
        <v-text-field
          v-if="!isMobile"
          v-show="showSearch"

          ref="inputSearch"
          v-model="inputSearchItems"
          :loading="loading"

          color="secondary"
          variant="outlined"
          density="comfortable"
          clearable
          prepend-inner-icon="mdi-magnify"
          hide-no-data
          hide-details
          :placeholder="$t('search.placeholder')"
        ></v-text-field>
      </Transition>

      <v-btn icon @click="toggleInputSearch" :title="searchTitleValue">
        <v-icon>{{ iconSearch }}</v-icon>
      </v-btn>

      <div class="d-flex float-right">
        <LanguageSwitcher bgColor="primary" />
        <MenuMobile v-if="isMobile" :itemsMenu="processedMenu(menu, props.allRoutes)" bgColor="primary"></MenuMobile>
      </div>

    </v-toolbar>
  </v-app-bar>

  <div class="resultsHeader" :style="{ top: `${top}`}">
    <v-text-field
      v-if="isMobile"
      v-show="showSearch"

      ref="inputSearch"
      v-model="inputSearchItems"
      :loading="loading"

      color="secondary"
      variant="outlined"
      density="comfortable"
      clearable
      prepend-inner-icon="mdi-magnify"
      hide-no-data
      hide-details
      :placeholder="$t('search.placeholder')"
    ></v-text-field>
    <SearchListCard v-if="showSearch" :results="results" @click-clear="toggleInputSearch"></SearchListCard>
  </div>
</template>

<script setup>
import {computed, defineAsyncComponent, ref, watch} from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const SearchListCard = defineAsyncComponent(() => import('@/components/Items/SearchListCard.vue'));
const LanguageSwitcher = defineAsyncComponent(() => import('@/components/Layout/LanguageSwitcher.vue'))
const MenuMobile = defineAsyncComponent(() => import('@/components/Layout/MenuMobile.vue'));

import astroOtterLogo from '@/assets/images/logos/astro_otter_200-200.png'
import configs from "@/configs";
import {searchItems} from "@/services/autocompleteSearch";

// Data
const logo = ref(astroOtterLogo)
const menu = ref(configs.headerMenu);
const showSearch = ref(false);
const iconSearch = ref('mdi-magnify');

const inputSearch = ref(null);
const searchTitleValue = ref(t('search.open'));
const inputSearchItems = ref('');

const results = ref([]);
const loading = ref(false);

const isMobile = computed(() => {
  return screen.width <= 760;
});

// Props
const props = defineProps({
  allRoutes: {
    type: Array
  }
});

// Computed
const processedMenu = computed(() => buildMenu);

const top = computed(() => true === isMobile.value ? '48px' : '64px')

// Methods
const buildMenu = (items, allRoutes) => {
  return items.map(route => {
    let routeName = route.routeName;
    const routeItem = allRoutes.filter(route => route.name === routeName)[0];
    return {
      key: routeItem.meta.key,
      icon: routeItem.meta.icon ?? 'mdi-tooltip-text-outline',
      path: routeItem.path,
      text: t(`${routeName}.title`),
      description: t(`${routeName}.title`)
    };
  })
};
const toggleInputSearch = () => {
  showSearch.value = !showSearch.value;
  iconSearch.value = (false === showSearch.value) ? 'mdi-magnify': 'mdi-close';
  searchTitleValue.value = (false === showSearch.value) ? t('search.open') : t('search.close')
  if (false === showSearch.value) {
    results.value = [];
    inputSearchItems.value = '';
  } else {
    // console.log('Autofocus');
    // inputSearch.value.focus();
  }
};

/**
 * Run WS and set data
 */
watch(inputSearchItems, (newSearch) => {
  setTimeout(async () => {
    results.value = await searchItems(newSearch);
  }, 200);
});

</script>



