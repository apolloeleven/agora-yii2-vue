export default {
  namespaced: true,
  state: {
    loading: false,
    showModal: false,
  },
  actions: {
    showWorkspaceModal({commit}, payload) {
      commit('showWorkspaceModal', payload);
    },
    hideWorkspaceModal({commit}, payload) {
      commit('hideWorkspaceModal', payload);
    },
  },
  mutations: {
    showWorkspaceModal: (state) => {
      state.showModal = true;
    },
    hideWorkspaceModal: (state) => {
      state.showModal = false;
    },
  }
}