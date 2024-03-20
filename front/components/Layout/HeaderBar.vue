<template>
  <v-app-bar
    color="secondary"
    mode="shift"
    :density="!isMobile ? 'default' : 'compact'"
  >
    <v-toolbar color="secondary">
      <NuxtLink
        :to="{ name: 'index'}"
        :title="t('layout.homeAccess')"
      >
        <v-avatar class="mx-2">
          <v-img
            :src="logo"
            :alt="t('layout.logo')"
          />
        </v-avatar>
      </NuxtLink>
      <v-divider
        vertical
        thickness="2"
        inset
        :class="!isMobile ? 'ml-5 mr-1' : 'mr-1'"
      />
      <div v-if="!isMobile">
        <v-btn
          v-for="(menuItem, index) in headerMenu"
          :key="index"
          class="text-none"
        >
          <router-link :to="menuItem.path">
            <span class="text-grey">{{ $t(menuItem.text) }}</span>
          </router-link>
        </v-btn>
      </div>

      <v-spacer />
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
        />
      </Transition>

      <v-btn
        icon
        :title="searchTitleValue"
        @click="toggleInputSearch"
      >
        <v-icon>{{ iconSearch }}</v-icon>
      </v-btn>

      <div class="d-flex float-right">
        <LanguageSwitcher bg-color="primary" />
        <MenuMobile
          v-if="isMobile"
          :items-menu="headerMenu"
          bg-color="primary"
        />
      </div>
    </v-toolbar>
  </v-app-bar>
  <div
    class="resultsHeader"
    :style="{ top: `${top}`}"
  >
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
    />
    <SearchListCard
      v-if="showSearch"
      :dsos="dsoList"
      :constellations="constellationsList"
      @click-clear="toggleInputSearch"
    />
  </div>
</template>

<script setup lang="ts">
import {computed, defineAsyncComponent, ref, watch} from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const SearchListCard = defineAsyncComponent(() => import('@/components/Items/SearchListCard.vue'));
const LanguageSwitcher = defineAsyncComponent(() => import('@/components/Layout/LanguageSwitcher.vue'))
const MenuMobile = defineAsyncComponent(() => import('@/components/Layout/MenuMobile.vue'));

import astroOtterLogo from '@/assets/images/logos/astro_otter_200-200.png'
import {useHeaderMenu} from "~/composables/menu/useHeaderMenu";

// Data
const logo = ref<string>(astroOtterLogo)
const showSearch = ref<boolean>(false);
const iconSearch = ref<string>('mdi-magnify');

const inputSearch = ref(null);
const searchTitleValue = ref(t('search.open'));
const inputSearchItems = ref('');

const dsoList = ref<SearchDsoItem[]>([]);
const constellationsList = ref<SearchConstellationItem[]>([]);
const loading = ref(false);

const { headerMenu } = useHeaderMenu();
const { isMobile } = useDevice();

// Computed
// const processedMenu = computed(() => buildMenu);
const top = computed(() => isMobile ? '48px' : '64px')

const toggleInputSearch = () => {
  showSearch.value = !showSearch.value;
  iconSearch.value = (false === showSearch.value) ? 'mdi-magnify': 'mdi-close';
  searchTitleValue.value = (false === showSearch.value) ? t('search.open') : t('search.close')
  if (false === showSearch.value) {
    dsoList.value = [];
    constellationsList.value = [];
    inputSearchItems.value = '';
  } else {
    // console.log('Autofocus');
    // inputSearch.value.focus();
  }
};

/**
 * Run WS and set data
 */
watch(inputSearchItems, (newSearch: string) => {
  setTimeout(async () => {
    /*results.value =*/ await useSearchRequest(newSearch);
  }, 200);
});

</script>



