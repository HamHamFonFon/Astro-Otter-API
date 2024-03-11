<script setup>
import {onMounted} from "vue";

import { VSonner, toast } from 'vuetify-sonner'
import 'vuetify-sonner/style.css'
import { mercureConfig } from '@/configs/mercure';

const getNotifications = () => {
  const hubUrl = new URL(mercureConfig.url);
  hubUrl.searchParams.append('topic', `${mercureConfig.topic}`);

  const eventSource = new EventSource(hubUrl.toString(), { withCredentials: true });
  eventSource.onmessage = (e) => {
    const response = JSON.parse(e.data);

    toast(response.message, {
      description: response.date,
      cardProps: {
        color: response.type ?? 'success'
      },
      prependIcon: 'mdi-check-circle'
    });
  };

  eventSource.onerror = () =>  {
    console.log("An error occurred while attempting to connect to Mercure Hub.")
    eventSource.close();
  }
}

onMounted(() => getNotifications())
</script>

<template>
  <VSonner expand position="bottom-left" :duration="10000" />
</template>

