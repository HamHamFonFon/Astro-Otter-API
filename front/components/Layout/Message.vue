<template>
  <div v-if="isLoading" class="d-flex justify-center text-left ma-4">
    <v-col sm="8">
      <v-alert
        :type="computedType"
        border="start"
        prominent
        closable
      >
<!--        <img :src="imgLoading" v-if="'warning' === computedType" style="width: 32px;" :alt="computedType">-->
        <div id="blockMessage">
          <v-progress-circular :color="computedType" indeterminate v-if="'warning' === computedType"></v-progress-circular>
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

// export default {
//
//   computed: {
//     ...mapState({
//       msgError: state => state.message.message,
//       type: state => state.message.type,
//       loading: state => state.message.loading
//     }),
//     computedMsg() {
//       return this.msgError;
//     },
//     computedType() {
//       return this.type
//     },
//     isLoading() {
//       return this.loading
//     }
//   }
// }
</script>

<style lang="scss">
</style>
