<script setup lang="ts">
import {defineAsyncComponent} from "vue";

const Message = defineAsyncComponent(() => import('~/components/Layout/Message.vue'))
const DsoCard = defineAsyncComponent(() => import('~/components/Items/DsoCard.vue'))

const { locale } = useI18n();

const store = useMessageStore();
store.$patch({
  type: 'info',
  loading: true,
  message: 'Load data in progress'
})

const {
  data: randomItems,
  pending,
  error,
  status,
  refresh
} = await useAsyncData(
  'randomItems',
  () => useCustomFetch<[]>('/dso/random', {
    method: 'GET',
    query: {
      limit: 3
    }
  })
);

console.log(randomItems.value);
if ('success' === status.value) {
  store.$patch({
    loading: false,
  })
} else {
  store.$patch({
    message: 'An error occured.',
    type: 'error',
    loading: false,
  })
}
watch(locale, () => refresh());
</script>
<template>
  <v-sheet
    elevation="0"
    class="mx-auto landing-warpper"
    rounded
    color="primary"
  >
    <v-sheet
      max-width="1600"
      class="mx-auto pa-5"
      elevation="0"
      color="transparent"
    >
      <v-container>
        <v-row align="center">

          <div v-if="pending">
            <Message />
          </div>
          <div v-else-if="error">
            <Message />
          </div>

<!--          <div v-else>-->
<!--            {{ JSON.stringify(randomItems) }}-->
<!--          </div>-->

<!--          <v-col-->
<!--            v-for="item in randomItems"-->
<!--            v-else-->
<!--            :key="item.id"-->
<!--            cols="12"-->
<!--            md="4"-->
<!--          >-->
<!--            <DsoCard :dso="item" />-->
<!--          </v-col>-->
        </v-row>
      </v-container>
    </v-sheet>
  </v-sheet>
</template>
