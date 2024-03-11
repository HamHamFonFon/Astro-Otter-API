<template>
  <v-btn
    :color="backgroundColor"
    @click="$event => showButtons = !showButtons"
    class="hover-button elevation-10"
    aria-label="{{ $t('share.open') }}"
  >
    <v-icon color="green" v-if="!showCloseButton">mdi-share-variant</v-icon>
    <v-icon v-else>mdi-close</v-icon>
  </v-btn>
  <v-divider />
  <transition name="slide-y">
    <v-card
        v-if="showButtons"
        elevation="10"
        class="d-flex flex-column mb-1 hovered-buttons"
        :color="backgroundColor"
    >
      <!-- Open / hide-->
      <v-btn
          @click="showButtons = false"
          variant="text"
          size="50"
          color="error"
      >
        <v-icon size="30">mdi-close</v-icon>
        <v-tooltip
            activator="parent"
            location="left"
            :text="$t('layout.btnClose')"
        ></v-tooltip>
      </v-btn>

      <v-divider />

      <!-- Social networls -->
      <v-btn
          color="transparent"
          size="50"
          v-for="socialNetwork in socialNetworks"
          v-bind:key="socialNetwork"
          @click="resolveFunction(socialNetwork.funcName, socialNetwork.param)"
          :aria-label="socialNetwork.label"
      >
        <v-icon size="30">{{ socialNetwork.icon }}</v-icon>
        <v-tooltip
            activator="parent"
            location="left"
            :text="socialNetwork.label"
        ></v-tooltip>
      </v-btn>

      <v-btn
          color="transparent"
          size="50"
          @click="pasteUrl"
          :aria-label="copyLink.text"
      >
        <v-icon size="30" :icon="copyLink.icon" :color="copyLink.color"></v-icon>
        <v-tooltip
            activator="parent"
            location="left"
            :text="copyLink.text"
        ></v-tooltip>
      </v-btn>
    </v-card>
  </transition>
</template>

<script setup>
import {computed, reactive, ref} from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();

const showButtons = ref(false);
const showCloseButton = ref(false);
const socialNetworks = reactive([
  // { label: 'TikTok', icon: 'mdi-tiktok'},
  {name: 'instagram', label: t('share.instagram'), icon: 'mdi-instagram', funcName: 'shareOn', param: ''},
  {name: 'twitter', label: t('share.twitter'), icon: 'mdi-twitter', funcName: 'shareOn', param: 'https://twitter.com/intent/tweet?text='},
  {name: 'facebook', label: t('share.facebook'), icon: 'mdi-facebook', funcName: 'shareOn', param: 'https://www.facebook.com/sharer/sharer.php?u='},
  {name: 'messenger', label: t('share.messenger'), icon: 'mdi-facebook-messenger', funcName: 'shareOn', param: 'fb-messenger://share/?link='},
  {name: 'whatsapp', label: t('share.whatsapp'), icon: 'mdi-whatsapp', funcName: 'shareOn', param: 'whatsapp://send?text='},
  {name: 'linkedin', label: t('share.linkedin'), icon: 'mdi-linkedin', funcName: 'shareOn', param: 'https://www.linkedin.com/sharing/share-offsite/?url='},
  // {name: 'twitch', label: 'Share on Twitch', icon: 'mdi-twitch'},
  {name: 'email', label: t('share.email'), icon: 'mdi-email', funcName: '', param: 'mailto:?subject=Check this link'},
]);

const copyLink = ref({
  text: t('layout.copy'),
  icon: 'mdi-bookmark-outline',
  color: 'white'
});


const backgroundColor = computed(() => (screen.width <= 760) ? 'primary': 'transparent')

const shareOn = (socialNetworkUrl) => {
  let shareUrl = socialNetworkUrl + encodeURIComponent(location.href);
  window.open(shareUrl, '_blank');
}

const pasteUrl = () => {
  copyLink.value.text = t('layout.copied');
  copyLink.value.icon = 'mdi-check-all';
  copyLink.value.color = 'green';
  navigator.clipboard.writeText(location.href);
}

const resolveFunction = (funcName, param) => {
  if ('shareOn' === funcName) {
    shareOn(param);
  }

  if ('pasteUrl' === funcName) {
    pasteUrl();
  }
  // if (typeof resolveFunction[funcName] === 'function') {
  //   resolveFunction[funcName](param);
  // } else {
  //   console.error(`Function ${funcName} not found`);
  // }
}

</script>

<style lang="scss">
@import "@/assets/scss/components/socialSharing.scss";
</style>
