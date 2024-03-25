<script setup lang="ts">
import type {RouteParamValue} from "vue-router";
import type {UnwrapRef} from "vue";

definePageMeta({
  layout: 'page'
});

const Message = defineAsyncComponent(() => import('@/components/Layout/Message.vue'));
const TitlePage = defineAsyncComponent(() => import('@/components/Content/TitlePage.vue'));

const { t, locale } = useI18n();
const route = useRoute()
const uid: ComputedRef<string | RouteParamValue[]> = computed(() => route.params.uid);

const { client, asText, asDate } = usePrismic();
const prismicLocale: Ref<UnwrapRef<string>> = ref(useLanguagesCode().languagesCodes.filter((item) => locale.value === item.locale)[0].prismic);

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('layout.load');

const {
  data: document,
  pending,
  error
} = await useLazyAsyncData( 'page', async () => {
    const document = client.getByUID('editorial_page', (uid.value as string), {lang: prismicLocale.value});
    if (document) {
      return document;
    } else {
      throw createError({ statusCode: 404, message: "Page not found" });
    }
}, {
  watch: [
    uid,
    prismicLocale
  ]
});

if (null !== error.value) {
  pending.value = true;
  type.value = 'error';
  message.value = `Error while loading page: ${error.value?.message}`;
}
applySeo({
  title: asText(document?.value?.data.seo_title),
  description: asText(document?.value?.data.seo_description),
  image: "@/assets/images/logos/astro_otter_200-200.png",
  fullUrl: 'https://change-it.com'
})

watch(locale, (newLocale) => {
  prismicLocale.value = useLanguagesCode().languagesCodes.filter((item) => newLocale === item.locale)[0].prismic
})

</script>

<template>
  <transition v-if="pending">
    <Message />
  </transition>
  <transition
    v-else-if="document"
    name="fade"
  >
    <v-sheet
      elevation="0"
      class="landing-warpper"
      color="transparent"
    >
      <TitlePage :title="asText(document?.data?.title) as string" />
      <v-container class="text-justify">
        <v-sheet
          elevation="0"
          class="mx-auto landing-warpper"
          rounded
          color="background"
        >
          <v-sheet
            class="pa-3"
            elevation="0"
            color="transparent"
          >
            <v-container>
              <prismic-rich-text
                class="richtext"
                :field="document?.data?.content"
              />
              <v-divider />
              <p>{{ document?.data?.last_update }}</p>
            </v-container>
          </v-sheet>
        </v-sheet>
      </v-container>
    </v-sheet>
  </transition>
</template>

<style scoped>

</style>
