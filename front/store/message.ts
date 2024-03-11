import { defineStore } from '@nuxt/bridge';

export interface MessageState {
  message: string | null;
  type: string;
  httpCode: number | 0;
  loading: boolean;
}

const state: MessageState = {
  message: null,
  type: 'warning',
  httpCode: 0,
  loading: true
}

const actions = {
    postError({ commit }: any, errorMsg: string): void {
        commit('setError', errorMsg);
    }
};

const mutations = {
    setMessage: (state: MessageState, { loading, type, message, httpCode }): void => {
        state.loading = loading;
        state.type = type;
        state.message = message;
        state.httpCode = httpCode;
    },
    setLoading: (state: MessageState, payload: boolean): void => {
        state.loading = payload;
    },
};

export const messageStore = defineStore({
  state,
  mutations,
  actions,
}).with({ namespaced: true });
