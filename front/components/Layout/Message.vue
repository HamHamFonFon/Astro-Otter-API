<template>
  <div
    v-if="isLoading"
    class="d-flex justify-center text-left ma-4"
  >
    <v-col sm="8">
      <v-alert
        :type="computedType"
        border="start"
        prominent
        closable
      >
        <div id="blockMessage">
          <v-progress-circular
            v-if="'warning' === computedType"
            :color="computedType"
            indeterminate
          />
          <span class="text-h6 text-left">{{ computedMsg }}</span>
        </div>
      </v-alert>
    </v-col>
  </div>
</template>

<script setup>
import {computed} from "vue";
import { useStore } from "vuex";
const store = useStore();

const computedMsg = computed(() => store.state.message.message);
const computedType = computed(() => store.state.message.type);
const isLoading = computed(() => store.state.message.loading);
</script>
