<template>
  <component :is="svgContent" class="fill-curent"></component>
</template>

<script setup>
import {onErrorCaptured, onMounted, ref, toRefs,} from "vue";

const svgContent = ref(null);
const props = defineProps({
  iconName: {
    type: String,
    required: true
  }
})
const { iconName } = toRefs(props)

// const svgContent = defineAsyncComponent(() => import(`@/assets/svg/${iconName.value}.svg`))

onMounted(() => {
  try {
    const svgResponse = import(`@/assets/svg/${iconName.value}.svg`);
    svgContent.value = svgResponse.default;
  } catch (error) {
    console.error(`Error loading SVG: ${iconName.value}.svg`, error);
  }
})

onErrorCaptured((error) => {
  console.error(`Error in CustomIcon component: ${error}`);
});
</script>
