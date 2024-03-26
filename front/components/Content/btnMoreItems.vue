<template>
  <v-btn
    :prepend-icon="icon"
    size="x-large"
    :loading="btnLoading"
    variant="outlined"
    class="text-white mr-5"
    color="grey"
    :aria-label="label"
    @click="$emit('click-event', ($event.target as HTMLInputElement).value)"
  >
    <span>{{ label }}</span>
  </v-btn>
</template>

<script setup lang="ts">
import { toRefs } from "vue";

interface Props {
  label?: string,
  icon?: string,
  btnLoading?: boolean
}
const props = withDefaults(defineProps<Props>(), {
  label: '',
  icon: 'mdi-plus',
  btnLoading: false
})

const { label, icon} = toRefs(props);

defineEmits(['click-event'])
</script>

<style scoped>

</style>
