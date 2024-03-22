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
          <v-col
            cols="12"
            md="5"
          >
            <p class="my-4 text-grey">
              {{ $t('home.explore') }}
            </p>
          </v-col>
          <v-col
            cols="12"
            md="7"
          >
            <div class="d-flex flex-wrap justify-center justify-md-end pb-5">
              <v-btn
                v-for="(socialNetwork, index) in socialNetworks"
                :key="index"
                color="blue-grey-darken-2"
                class="mx-3"
                :aria-label="socialNetwork.name"
                icon
                @click="openSocialNetwork(socialNetwork.to)"
              >
                <v-icon>{{ socialNetwork.icon }}</v-icon>
              </v-btn>
            </div>

            <div class="d-flex flex-wrap justify-center justify-md-end">
              <NuxtLink
                v-for="(nav, index) in footerPageitems"
                :key="index"
                class="text-primary mx-3 mb-3 font-weight-bold"
                :to="{name: nav.path }"
              >
                <span class="text-grey">{{ $t(nav.text) }}</span>
              </NuxtLink>

              <NuxtLink
                v-for="nav in document"
                :key="nav.id"
                class="text-primary mx-3 mb-3 font-weight-bold"
                :to="`/pages/${nav.uid}`"
              >
                <span class="text-grey">{{ asText(nav?.data?.title) }}</span>
              </NuxtLink>
            </div>
          </v-col>
        </v-row>
        <hr class="my-3">
        <p class="text-center my-5">
          {{ $t('footer.all_rights') }}
        </p>
      </v-sheet>
    </v-container>
  </v-sheet>
</template>

<script setup lang="ts">
import { type UnwrapRef } from "vue";

const { locale } = useI18n();
const { client, asText } = usePrismic();

const { socialNetworks } = useSocialNetworks();
const { footerPageitems } = useFooterpagesitems();
const prismicLocale: Ref<UnwrapRef<string>> = ref(useLanguagesCode().languagesCodes.filter((item) => locale.value === item.locale)[0].prismic);

const { data: document } = await useLazyAsyncData(
  'document',
  async () => {
    const document = client.getAllByType('editorial_page', {lang: prismicLocale.value})
    if (document) {
      return document;
    }
  },
  {
    watch: [
      prismicLocale
    ]
  }
);

watch(locale, (newLocale) => {
  prismicLocale.value = useLanguagesCode().languagesCodes.filter((item) => newLocale === item.locale)[0].prismic
})

const openSocialNetwork = (link: string): void => {
  window.open(link, '_blank')
};
</script>
