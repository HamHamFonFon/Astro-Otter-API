<script setup>
import { onMounted } from "vue";
import { VSonner, toast } from 'vuetify-sonner';

import { useRuntimeConfig } from 'nuxt/app';

const config = useRuntimeConfig();
import 'vuetify-sonner/style.css'

const getNotifications = () => {
  const hubUrl = new URL(config.public.mercurePublicUrl);
  hubUrl.searchParams.append('topic', `${config.public.mercureTopic}`);

  const eventSource = new EventSource(hubUrl.toString(), { withCredentials: true });
  eventSource.onmessage = (e) => {
    const response = JSON.parse(e.data);

    const toastOptions = {
      description: response.date ?? undefined,
      duration: 10000 ?? Number.POSITIVE_INFINITY,
      cardProps: {
        color: response.type ?? 'success'
      },
      prependIcon: 'mdi-check-circle'
    }
    toast(response.message, toastOptions);
  };

  eventSource.onerror = () =>  {
    console.log("An error occurred while attempting to connect to Mercure Hub.")
    eventSource.close();
  }
}

onMounted(() => getNotifications())
</script>

<template>
  <VSonner
    expand
    position="bottom-left"
  />
</template>

