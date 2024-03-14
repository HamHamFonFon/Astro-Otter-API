<script setup>
import CountryFlag from 'vue-country-flag-next'
import {toRefs} from "vue";

const props = defineProps({
  btnColor: {
    type: String,
    default: ''
  },
  iconColor: {
    type: String,
    default: 'text-grey'
  },
  bgColor: {
    type: String,
    default: 'background'
  }
});

const { btnColor, iconColor, bgColor } = toRefs(props);

const listFlags = ref({
  'en': 'gbr',
  'fr': 'fra'
})
const { locale, availableLocales } = useI18n();
const { setLocale } = useI18n();
const currentLang = ref(locale);
const switchLanguage = async (newLocale) => {
  setLocale(newLocale);
  currentLang.value = newLocale;
}
</script>

<template>
  <v-menu class="float-right">
    <template #activator="{ props }">
      <v-btn
        icon
        v-bind="props"
        :color="btnColor"
        aria-label="{{ $t('layout.languageSwitcher')}}"
      >
        <v-icon :color="iconColor">
          mdi-translate
        </v-icon>
      </v-btn>
    </template>
    <v-list
      nav
      :bg-color="bgColor"
    >
      <v-list-item
        v-for="lang in availableLocales"
        :key="lang"
        density="compact"
        :active="lang === locale"
        @click="switchLanguage(lang)"
      >
        <template #prepend>
          <country-flag :country="listFlags[lang]" />
        </template>
        <v-list-item-title
          class="mt-2"
          style="padding: 0 0.5em;"
        >
          {{ $t(`languages.${lang}`) }}
        </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<style scoped>

</style>
