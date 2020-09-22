import invitationService from "../../modules/User/Invitation/invitationService";

export default {
  namespaced: true,
  state: {
    showModal: false,
    modalInvitation: {},
    loading: false,
    data: {
      rows: []
    },

  },
  actions: {
    async getAll({commit}, keyword) {
      commit('changeLoading');
      const res = await invitationService.get(keyword);
      commit('changeLoading');
      commit('getAll', {rows: res.body});
    },
    showInvitationModal({commit}) {
      commit('showInvitationModal');
    },
    hideModal({commit}) {
      commit('hideModal');
    },
    async inviteUser({dispatch}, email) {
      const res = await invitationService.invite(email);
      if (res.success) {
        dispatch('getAll');
      }
      return res;
    },
    async delete({dispatch}, id) {
      const res = await invitationService.delete(id);
      if (res.success) {
        dispatch('getAll');
      }
      return res;
    }
  },
  mutations: {
    getAll: (state, {rows}) => {
      state.data.rows = rows;
    },
    showInvitationModal: (state) => {
      state.showModal = true;
      state.modalInvitation = {};
    },
    hideModal: (state) => {
      state.showModal = false;
      state.modalUser = {};
    },
    changeLoading: state => state.loading = !state.loading
  }
}