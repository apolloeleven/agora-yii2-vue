import invitationService from "../../modules/User/Invitation/invitationService";

export default {
  namespaced: true,
  state: {
    showModal: false,
    modalInvitation: {},
    loading: false,
    data: {
      total: 0,
      perPage: 20,
      page: 1,
      rows: []
    },

  },
  actions: {
    async getAll({commit}, keyword) {
      commit('changeLoading');
      const res = await invitationService.get(keyword);
      commit('changeLoading');
      commit('getAll', {
        rows: res.body,
        total: res.response.headers['x-pagination-total-count'],
        perPage: res.response.headers['x-pagination-per-page'],
        page: res.response.headers['x-pagination-current-page']
      });
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
    getAll: (state, {rows, total, perPage, page}) => {
      state.data.rows = rows;
      state.data.total = total;
      state.data.perPage = perPage;
      state.data.page = page;
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