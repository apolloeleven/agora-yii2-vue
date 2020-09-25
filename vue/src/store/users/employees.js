import employeesService from "../../modules/User/Employees/employeesService"
import httpService from "@/core/services/httpService";
import EmployeeFormModal from "@/modules/User/Employees/EmployeeFormModal";

export default {
  namespaced: true,
  state: {

    modal: {
      show: false,
      object: {}
    },

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
      state.modal.show = true;
      state.modal.object = {
        id: payload.id,
        email: payload.email,
        roles: payload.roles,
        userDepartments: payload.userDepartments,
        ...payload.userProfile
      }
    },
    changeLoading: state => state.loading = !state.loading,
    hideModal: (state) => {
      state.modal.show = false;
      state.modal.object = {};
    }
  }
}