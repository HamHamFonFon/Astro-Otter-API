<template>
  <v-btn
      :prepend-icon="icon"
      size="x-large"
      :loading="btnLoading"
      variant="outlined"
      class="text-white mr-5"
      color="grey"
      @click="clickEvent"
      :aria-label="label"
  > <span>{{ label }}</span> </v-btn>
</template>

<script setup>
import { toRefs } from "vue";

const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: 'mdi-plus'
  },
  btnLoading: {
    type: Boolean,
    default: false
  }
});
const { label, icon} = toRefs(props);
const emit = defineEmits(['click-event']);
const clickEvent = () => {
  emit('click-event');
}
</script>

<style scoped>

</style>
