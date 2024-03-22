<script setup lang="ts">
import {definePageMeta} from "#imports";
import type {RouteParamValue} from "vue-router";

const Message = defineAsyncComponent(() => import('@/components/Layout/Message.vue'))
const TitleImageHero = defineAsyncComponent(() => import('@/components/Content/TitleImageHero.vue'))
const DsoBrowser = defineAsyncComponent(() => import('@/components/Content/DsoBrowser.vue'))

const route = useRoute()
const id: ComputedRef<string | RouteParamValue[]> = computed(() => route.params.id);

const { t } = useI18n();

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = t('constellation.load.data');

definePageMeta({
  layout: 'page'
})

const {
  data,
  pending,
} = await useCustomFetch<Constellation>(`/constellation/item/${(id.value as string)}`, {
  method: 'GET',
})

function getCoverUrl(): string {
  return new URL(`/assets/images/constellations/cover/${data?.value?.cover}`, import.meta.url).href
}

</script>

<template>
  <DelayHydration>
    <transition
      v-if="pending"
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
        <TitleImageHero
          :title="data?.alt"
          :url-image="getCoverUrl()"
        />
        <v-container class="text-left">
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-container
                class="text-justify"
              >
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
                      <div class="richtext">
                        <p>{{ data?.description }}</p>
                      </div>
                    </v-container>
                  </v-sheet>
                </v-sheet>
              </v-container>
            </v-col>
          </v-row>

          <DsoBrowser
            default-filter-name="constellation"
            :default-filter-value="data?.id"
          />
        </v-container>
      </v-sheet>
    </transition>
  </DelayHydration>
</template>

<style scoped>

</style>
