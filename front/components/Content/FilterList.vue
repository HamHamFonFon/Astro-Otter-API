<template>
  <v-sheet
      color="transparent"
      elevation="0"
      max-width="1600"
      class="mx-auto pa-5"
  >
    <v-container>
      <v-row align="center" justify="center">
        <v-col cols="12" sm="12" md="9">
          <v-text-field
            :value="modelValue"
            v-on:input="updateValue($event.target.value)"

            :label="label"
            :placeholder="placeholder"
            variant="outlined"
            rounded
            clearable
          ></v-text-field>
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>
</template>

<script setup>
import {toRefs} from "vue";

const props = defineProps({
  modelValue: {
    type: String,
    default: null
  },
  label: {
    type: String,
    default: null
  },
  placeholder: {
    type: String,
    default: null
  }
});
const { modelValue, label, placeholder } = toRefs(props);

const emit = defineEmits(['update:modelValue']);
const updateValue = (value) => {
  emit('update:modelValue', value)
}
</script>

<style scoped>

</style>