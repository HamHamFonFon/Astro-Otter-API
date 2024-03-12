import { useAuthStore } from "~/store/auth";
const authStore= useAuthStore();

export async function login(): Promise<boolean> {
  return authStore.fetchLogin();
}

export async function refreshToken(): Promise<boolean> {
  return authStore.fetchRefreshToken();
}
