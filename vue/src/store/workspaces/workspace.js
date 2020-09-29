import workspaceService from "../../modules/Workspace/workspaceService";

export default {
  namespaced: true,
  state: {
    loading: false,
    showModal: false,
    workspaces: [],
    userWorkspaces: [],
    modalWorkspace: null,
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
        dispatch('getUserWorkspaces');
        return res
      }
    },
    async updateWorkspace({dispatch}, payload) {
      const res = await workspaceService.update(payload);
      if (res.success) {
        dispatch('getUserWorkspaces');
        return res;
      }
    },
    async deleteWorkspace({commit, dispatch}, workspace) {
      const res = await workspaceService.delete(workspace.id);
      if (res.success) {
        commit('workspaceDeleted', workspace.id);
        dispatch('getUserWorkspaces');
        return res;
      }
    },
    async getUserWorkspaces({commit}) {
      const res = await workspaceService.getUserWorkspaces();
      if (res.success) {
        commit('getUserWorkspaces', res.body);
      }
    },
  },
  mutations: {
    showWorkspaceModal: (state, workspace) => {
      state.showModal = true;
      state.modalWorkspace = workspace;
    },
    hideWorkspaceModal: (state) => state.showModal = false,
    getUserWorkspaces: (state, data) => {
      state.userWorkspaces = data;
      state.workspaces = data;
    },
    workspaceDeleted: (state, id) => state.workspaces = state.workspaces.filter(workspace => workspace.id !== id),
  }
}