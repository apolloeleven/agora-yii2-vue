import {SET_DATA, SET_MODAL_DROPDOWN_DATA, SHOW_EMPLOYEE_MODAL, CHANGE_LOADING, HIDE_MODAL} from "@/store/modules/employee/mutation-types";

export default {
  [SET_DATA]: (state, {rows}) => {
    state.data.rows = rows;
  },

  [SET_MODAL_DROPDOWN_DATA]: (state, {userRoles, userPositions, countries, departments}) => {
    state.modalDropdownData.userRoles = userRoles;
    state.modalDropdownData.userPositions = userPositions;
    state.modalDropdownData.countries = countries;
    state.modalDropdownData.departments = departments;
  },

  [SHOW_EMPLOYEE_MODAL]: (state, payload) => {
    state.modal.show = true;
    state.modal.object = {
      id: payload.id,
      email: payload.email,
      first_name: payload.first_name,
      last_name: payload.last_name,
      roles: payload.roles,
      userDepartments: payload.userDepartments,
    }
  },

  [CHANGE_LOADING]: (state) => state.loading = !state.loading,

  [HIDE_MODAL]: (state) => {
    state.modal.show = false;
    state.modal.object = {};
  }
}