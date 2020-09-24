import employeesService from "../../modules/User/Employees/employeesService"
export default {
  namespaced: true,
  state: {
    showModal: false,
    modalEmployee: {},
    loading: false,
    data: {
      rows: []
    }
  },
  actions: {
    showEmployeeModal({commit}, payload) {
      commit('showEmployeeModal', payload);
    },
    async getData({commit}, payload) {
      commit('changeLoading');
      const res = await employeesService.get(payload);
      commit('changeLoading');
      commit('getData', {rows: res.body});
    }
  },
  mutations: {
    getData: (state, {rows}) => {
      state.data.rows = rows;
    },
    showEmployeeModal: (state, payload) => {
      state.showModal = true;
      state.modalEmployee = {
        firstName: payload.userProfile.first_name,
        lastName: payload.userProfile.last_name,
        email: payload.email
      };
    },
    changeLoading: state => state.loading = !state.loading
  }
}