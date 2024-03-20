import { defineStore } from 'pinia';

interface MessageState {
  message: string | null;
  type: string;
  httpCode: number | 0;
}

export const useMessageStore = defineStore('message', {
  state: (): MessageState => {
    return {
      message: null,
      type: 'warning',
      httpCode: 0,
    }
  },
  actions: { },
  getters: { },
  // mutations: {
  //   setMessage: (state: MessageState, { loading, type, message, httpCode }: {loading: boolean; type: string; message: string; httpCode: number}): void => {
  //     state.loading = loading;
  //     state.type = type;
  //     state.message = message;
  //     state.httpCode = httpCode;
  //   },
  //   setLoading: (state: MessageState, payload: boolean): void => {
  //     state.loading = payload;
  //   },
  // }
});
