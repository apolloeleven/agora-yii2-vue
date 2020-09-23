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
    showEmployeeModal({commit}) {
      commit('showEmployeeModal')
    },
    async getData({commit}, payload) {
      commit('changeLoading');
      console.log(111);
      const res = await employeesService.get(payload);
      commit('changeLoading');
      commit('getData', {rows: res.body});
    }
  },
  mutations: {
    getData: (state, {rows}) => {
      state.data.rows = rows;
    },
    showEmployeeModal: (state) => {
      state.showModal = true;
      state.modalEmployee = {};
    },
    changeLoading: state => state.loading = !state.loading
  }
}