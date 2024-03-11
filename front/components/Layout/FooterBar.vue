<template>
    <v-sheet
        elevation="0"
        class="landing-warpper"
        color="secondary"
    >
      <v-container class="text-left pa-10">
        <v-sheet
            class="mx-auto"
            color="transparent"
            elevation="0"
            max-width="1600"
        >
          <v-row>
            <v-col cols="12" md="5">
              <p class="my-4 text-grey">{{ $t('home.explore') }}</p>
            </v-col>
            <v-col cols="12" md="7">
              <div class="d-flex flex-wrap justify-center justify-md-end pb-5">
                <v-btn v-for="(socialNetwork, index) in socialNetworks"
                   v-bind:key="index"
                   color="blue-grey-darken-2"
                   class="mx-3"
                   @click="openSocialNetwork(socialNetwork.to)"
                   icon
                   :aria-label="socialNetwork.name"
                >
                  <v-icon>{{ socialNetwork.icon }}</v-icon>
                </v-btn>
              </div>

              <div class="d-flex flex-wrap justify-center justify-md-end">
                <router-link
                    class="text-primary mx-3 mb-3 font-weight-bold"
                    v-for="nav in processedFooterMenu(footerPages, props.allRoutes)"
                    :to="nav.path"
                    v-bind:key="nav.key"
                >
                  <span class="text-grey">{{ nav.text }}</span>
                </router-link>

                <router-link
                  class="text-primary mx-3 mb-3 font-weight-bold"
                  v-for="nav in prismicRoutes"
                  :to="{name: 'primisc_content_page', params: {'uid': nav.path}}"
                  v-bind:key="nav.key"
                >
                  <span class="text-grey">{{ nav.text }}</span>
                </router-link>
              </div>
            </v-col>
          </v-row>
          <hr class="my-3" />
          <p class="text-center my-5">{{ $t('footer.all_rights') }}</p>
        </v-sheet>
      </v-container>
  </v-sheet>
</template>

<script setup>
import configs from "@/configs";
import {computed, onMounted, reactive, ref, watch} from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
import {usePrismic} from "@prismicio/vue";
const { client, predicate, asText } = usePrismic();

import Trans from "@/services/translation";
const socialNetworks = ref(configs.socialNetworks);
const footerPages = ref(configs.footerPages);

const props = defineProps({
  allRoutes: {
    type: Array
  },
});
const prismicRoutes = reactive([]);

onMounted(async () => {
  await processedFooterPrismic();
})

watch(() => Trans.currentLocale, async () => {
  prismicRoutes.length = 0;
  await processedFooterPrismic();
})

const openSocialNetwork= (link) => {
  window.open(link, '_blank')
};

const buildMenu = (footerPages, allRoutes) => {
  return footerPages.map(route => {
    let routeName = route.routeName;
    const routeItem = allRoutes.filter(route => route.name === routeName)[0];
    let path = routeItem.path;
    return {
      key: routeItem.meta.key,
      text: t(`${routeName}.title`),
      path: path
    }
  });
};

const processedFooterMenu = computed(() => buildMenu );
const processedFooterPrismic = async () => {
  try {
    const items = await client.query(predicate.at('document.type', 'editorial_page'), { lang: Trans.getPrismicLocale() });
    if (0 === items.results_size) {
      return null;
    }
    items.results.forEach(i => {
      prismicRoutes.push({
        key: i.id,
        text: asText(i.data.title),
        path: i.uid
      })
    })
  } catch (e) {
    console.error(e.message);
  }

};
</script>

<style scoped>

</style>
