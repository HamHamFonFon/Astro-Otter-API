<template>

  <v-sheet
    color="transparent"
    elevation="0"
    max-width="1600"
    class="mx-auto pa-5"
  >
    <v-container>
      <v-row
        align="center"
        justify="center"
      >
        <v-col
          cols="12"
          sm="12"
          md="9"
        >
          <v-text-field
            clearable
            :label="label"
            variant="outlined"
            :placeholder="placeholder"
            rounded
            @input=" $emit('update:modelValue', ($event.target as HTMLInputElement).value)"
          />
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>
</template>

<script setup lang="ts">
import {toRefs} from "vue";

// source: https://dev.to/denisseab/how-to-emit-a-value-from-an-input-component-vue-3-1lla

const props = defineProps<{
  modelValue?: string,
  label?: string,
  placeholder?: string
}>();
const { modelValue, label, placeholder } = toRefs(props);

defineEmits(["update:modelValue"]);
</script>

<style scoped>

</style>
