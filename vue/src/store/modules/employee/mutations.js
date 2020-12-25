import {
  SET_DATA,
  SET_MODAL_DROPDOWN_DATA,
  SHOW_EMPLOYEE_MODAL,
  CHANGE_LOADING,
  HIDE_MODAL,
  DELETED_USER,
  CHANGE_USER_ROLE, REMOVE_USER_FROM_WORKSPACE
} from "./mutation-types";

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
      ...payload
    };
  },

  [CHANGE_LOADING]: (state) => state.loading = !state.loading,

  [HIDE_MODAL]: (state) => {
    state.modal.show = false;
    state.modal.object = {};
  },

  [DELETED_USER](state, id) {
    state.data.rows = state.data.rows.filter(a => a.id !== id)
  },

  [CHANGE_USER_ROLE](state, {userId, role}) {
    const user = state.data.rows.find(u => u.id === userId);
    if (user)  user.role = role;
  },
  [REMOVE_USER_FROM_WORKSPACE](state, {userId}) {
    state.data.rows = state.data.rows.filter(u => u.id !== userId);
  }
}