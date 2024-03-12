import { Store } from 'vuex';

interface MessageState {
  message: string | null;
  type: string;
  httpCode: number | 0;
  loading: boolean;
}

export const state = (): MessageState => ({
  message: null,
  type: 'warning',
  httpCode: 0,
  loading: true
});

export const actions = {
    // postError({ commit }: any, errorMsg: string): void {
    //     commit('setMessage', {true, '', errorMsg, 500});
    // }
};

export const mutations = {
    setMessage: (state: MessageState, { loading, type, message, httpCode }: {loading: boolean; type: string; message: string; httpCode: number}): void => {
        state.loading = loading;
        state.type = type;
        state.message = message;
        state.httpCode = httpCode;
    },
    setLoading: (state: MessageState, payload: boolean): void => {
        state.loading = payload;
    },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
