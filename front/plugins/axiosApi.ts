import axios from 'axios';
import { defineNuxtPlugin } from 'nuxt/app';

export default defineNuxtPlugin(nuxtApp => {
  const instance = axios.create({
    baseURL: '' //config.API_URL, // Set your API base URL
  });

  instance.interceptors.request.use(
    config => {
      if (store.getters["auth/isLoggedIn"]) {
        config.headers.Authorization = `Bearer ${store.state.auth.accessToken}`;
      }
      return config;
    },
    error => {
      return Promise.reject(error)
    }
  )

  nuxtApp.provide('axiosApi', axiosInstance);
});
