import employeesService from "../../modules/User/Employees/employeesService"

export default {
  namespaced: true,
  state: {
    showModal: false,
    modalEmployee: {},
    modalDropdownData: {},
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
    },
    async getModalDropdownData({commit}) {
      const res = await employeesService.getModalDropdownData()
      commit('getModalDropdownData', {rows: res.body})
    },
    hideModal({commit}) {
      commit('hideModal');
    }
  },
  mutations: {
    getData: (state, {rows}) => {
      state.data.rows = rows;
    },
    getModalDropdownData(state, {rows}) {
      state.modalDropdownData.userRoles = rows.userRoles;
      state.modalDropdownData.userPositions = rows.userPositions;
      state.modalDropdownData.countries = rows.countries;
      state.modalDropdownData.departments = rows.departments;
    },
    showEmployeeModal: (state, payload) => {
      state.showModal = true;
      state.modalEmployee = {
        firstName: payload.userProfile.first_name,
        lastName: payload.userProfile.last_name,
        email: payload.email
      };
    },
    changeLoading: state => state.loading = !state.loading,
    hideModal: (state) => {
      state.showModal = false;
      state.modalEmployee = {};
    }
  }
}