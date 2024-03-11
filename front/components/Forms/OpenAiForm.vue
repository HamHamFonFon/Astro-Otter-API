<script setup>
import {reactive} from "vue";

const formSearch = reactive({
  term: ''
})

const emit = defineEmits(['submit-form']);
const submitSearchForm = () => emit('submit-form', { ...formSearch})
</script>

<template>
  <v-form @submit.prevent="submitSearchForm">
    <v-textarea
      v-model="formSearch.term"
      variant="outlined"
      auto-grow
      label="Search criteria"
      model-value="What are all nebula in orion constellation ?"
      clearable
    ></v-textarea>

    <v-btn
      type="submit"
      block
      size="x-large"
      variant="outlined"
      class="text-white mr-5"
      aria-label="{{ $t('layout.submit')}}"
    >
      {{ $t('layout.submit')}}
    </v-btn>
  </v-form>

</template>

<style scoped>

</style>
