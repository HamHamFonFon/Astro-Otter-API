import {useRuntimeConfig} from 'nuxt/app';
import {endpoint} from "~/composables/api/auth/endpoint";
import type {AxiosResponse} from 'axios';
import axios from "axios";
import * as WS from '@/composables/api/abstractWebservice'

const { apiPublicHost } = useRuntimeConfig();

const API_CREDENTIALS = {
  login: process.env.NUXT_API_LOGIN,
  password: process.env.NUXT_API_PWD
}

interface LoginResponse {
  jwtToken: string,
  refreshToken: string
}

export const GET_LOGIN = async (): Promise<LoginResponse> => {
  try {
    const requestBody: string = JSON.stringify({
      'email': API_CREDENTIALS.login,
      'password': API_CREDENTIALS.password,
    });

    const headersConfig = WS.buildApiHeaders(null, null, null);
    const response: AxiosResponse<any> = await axios.post(apiPublicHost + endpoint.LOGIN, requestBody, headersConfig);

    if (200 !== response.status) {
      throw new Error(response.statusText);
    }

    return {
      'jwtToken': response.data.token,
      'refreshToken': response.data.refresh_token
    }

  } catch (err: unknown) {
    throw new Error((err as Error).message);
  }
}
