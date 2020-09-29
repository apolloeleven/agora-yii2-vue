import workspaceService from "../../modules/Workspace/WorkspaceService";

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
    async createWorkspace({dispatch}, payload) {
      const res = await workspaceService.create(payload);
      if (res.success) {
        console.log(res)
        // dispatch('getUserWorkspaces');
        return res
      }
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