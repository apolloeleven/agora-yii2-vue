import employeesService from "../../modules/User/Employees/employeesService"
import httpService from "@/core/services/httpService";

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
      const res = await employeesService.getModalDropdownData();
      const response = await httpService.get('/v1/setup/countries', {
        params: {expand: 'departments',}
      });
      // TODO get countries from vuex setup module
      commit('getModalDropdownData', {
        ...res.body,
        countries: response.body
      })
    },
    hideModal({commit}) {
      commit('hideModal');
    }
  },
  mutations: {
    getData: (state, {rows}) => {
      state.data.rows = rows;
    },
    getModalDropdownData(state, {userRoles, userPositions, countries, departments}) {
      state.modalDropdownData.userRoles = userRoles;
      state.modalDropdownData.userPositions = userPositions;
      state.modalDropdownData.countries = countries;
      state.modalDropdownData.departments = departments;
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