<template>
  <div
    class="d-flex justify-center text-left ma-4"
  >
    <v-col sm="8">
      <v-alert
        :type="type"
        border="start"
        prominent
        closable
      >
        <div id="blockMessage">
          <v-progress-circular
            v-if="'warning' === type"
            :color="type"
            indeterminate
          />
          <span class="text-h6 text-left">{{ message }}</span>
        </div>
      </v-alert>
    </v-col>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
const { message, type } = storeToRefs(useMessageStore())

// const computedMsg = computed(() => store.$state.message);
// const computedType = computed(() => store.$state.type);
// const isLoading = computed(() => store.$state.loading);
</script>
