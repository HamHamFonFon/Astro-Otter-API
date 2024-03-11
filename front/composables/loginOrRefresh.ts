import { inject } from 'vue';
const store = inject('store');

export async function login(): Promise<any> {
  return store.dispatch('auth/fetchLogin');
}

export async function refreshToken(): Promise<any> {
  return store.dispatch('auth/fetchRefreshToken');
}
