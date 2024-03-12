import {useRuntimeConfig} from 'nuxt/app';
import {endpoint} from "~/composables/api/auth/endpoint";
import type {AxiosResponse} from 'axios';
import axios from "axios";
import * as WS from '@/composables/api/abstractWebservice'

const { apiPublicHost } = useRuntimeConfig();

interface RefreshResponse {
  jwtToken: string;
}

export const GET_REFRESH = async (refreshToken: string | null): Promise<RefreshResponse> => {
  if (null === refreshToken) {
    throw new Error(`Refresh token must be provided`);
  }
  try {
    const requestBody: {refresh_token: string} = {
      'refresh_token': refreshToken
    }

    const headersConfig = WS.buildApiHeaders(null, null, null);
    const response: AxiosResponse<any> = await axios.post(apiPublicHost + endpoint.REFRESH, requestBody, headersConfig);
    if (200 !== response.status) {
      throw new Error(response.statusText);
    }

    return {
      'jwtToken': response.data.token
    }
  } catch (err: unknown) {
    throw new Error((err as Error).message);
  }
}
