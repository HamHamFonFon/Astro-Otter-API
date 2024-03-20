<script setup lang="ts">
import { defineAsyncComponent } from "vue";

const { locale } = useI18n();

const Message = defineAsyncComponent(() => import('~/components/Layout/Message.vue'))
const DsoCard = defineAsyncComponent(() => import('~/components/Items/DsoCard.vue'))

const store = useMessageStore();
const { type, message } = storeToRefs(store);
type.value = 'warning';
message.value = 'Load data in progress';

const {
  data,
  pending,
  error,
  status,
  refresh
} = await useCustomFetch<Dso[]>('/dso/random', {
  method: 'GET',
  query: {
    limit: 3
  }
});

if ('success' !== status.value) {
  type.value = 'error';
  message.value = 'Error retrieve data';
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
        <div v-if="pending">
          <Message />
        </div>
        <div v-else-if="error">
          <Message />
        </div>
        <v-row
          v-else
          align="center"
        >
          <v-col
            v-for="(item, index) in data"
            :key="index"
            cols="12"
            md="4"
          >
            <DsoCard :dso="item" />
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>
  </v-sheet>
</template>
